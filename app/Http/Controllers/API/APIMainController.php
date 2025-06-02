<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Review;
use App\Helpers\MidtransHelper;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class APIMainController extends Controller
{
    // 1. Checkout - Cek apakah sudah pernah beli

    public function checkTransactionStatus(Request $request, $transactionCode)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning("User not authenticated for status check", ['transaction_code' => $transactionCode]);
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transaction = Transaction::where('transaction_code', $transactionCode)
                ->where('user_id', $user->id)
                ->first();

            if (!$transaction) {
                Log::warning("Transaksi tidak ditemukan", [
                    'transaction_code' => $transactionCode,
                    'user_id' => $user->id,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            if ($transaction->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'message' => 'Transaksi sudah dibayar.',
                ], 200);
            }

            // Check status with Midtrans
            MidtransHelper::config();
            $midtransStatus = (object) MidtransTransaction::status($transactionCode);

            Log::info("Midtrans status check", [
                'transaction_code' => $transactionCode,
                'midtrans_status' => $midtransStatus,
            ]);

            $newStatus = match ($midtransStatus->transaction_status) {
                'settlement', 'capture' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'cancelled',
                'deny' => 'failed',
                default => 'unknown',
            };

            if ($newStatus !== $transaction->status) {
                $transaction->update([
                    'status' => $newStatus,
                    'payment_type' => $midtransStatus->payment_type ?? null,
                    'payment_time' => $midtransStatus->transaction_time ?? null,
                    'midtrans_response' => json_encode($midtransStatus),
                ]);

                Log::info("Status transaksi diperbarui dari Midtrans", [
                    'transaction_code' => $transactionCode,
                    'new_status' => $newStatus,
                ]);

                if ($newStatus === 'paid' && $transaction->product && $transaction->product->seller) {
                    $transaction->product->seller->updateBalance();
                    Log::info("Saldo seller diperbarui", [
                        'seller_id' => $transaction->product->seller->id,
                        'amount' => $transaction->amount,
                        'transaction_code' => $transactionCode,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Status transaksi diperiksa.',
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat memeriksa status transaksi: " . $e->getMessage(), [
                'transaction_code' => $transactionCode,
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function checkout(Request $request, $productId)
    {
        try {
            $product = Product::with(['kategori', 'seller.user'])->find($productId);
            if (!$product) {
                Log::warning("Produk tidak ditemukan", ['product_id' => $productId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan.',
                ], 404);
            }

            $existingPaid = Transaction::where([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'status' => 'paid'
            ])->exists();

            if ($existingPaid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah pernah membeli produk ini.',
                    'has_bought' => true
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Produk tersedia untuk dibeli.',
                'product' => $product,
                'has_bought' => false
            ]);
        } catch (\Exception $e) {
            Log::error("Error saat checkout: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses checkout.',
            ], 500);
        }
    }

    // 2. Process Checkout - Buat transaksi & dapatkan snap token Midtrans
    public function processCheckout(Request $request, $productId)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'agree' => 'accepted',
            ]);

            $product = Product::findOrFail($productId);

            $existing = Transaction::where([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'status' => 'pending',
            ])->first();
            if ($existing) {
                Log::info("Pending transaction found", [
                    'code' => $existing->transaction_code,
                    'snap_token' => $existing->snap_token,
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Kamu sudah memiliki transaksi yang belum diselesaikan.',
                    'data' => [
                        'snap_token' => $existing->snap_token,
                        'transaction_code' => $existing->transaction_code,
                    ],
                ], 400);
            }

            $transactionCode = 'CSP-' . strtoupper(Str::random(8));
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'transaction_code' => $transactionCode,
                'amount' => $product->price,
                'status' => 'pending',
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $transactionCode,
                    'gross_amount' => $product->price,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => $request->email,
                ],
                'item_details' => [[
                    'id' => $product->id,
                    'price' => $product->price,
                    'quantity' => 1,
                    'name' => Str::limit($product->name, 20),
                ]],
            ];

            $snapToken = MidtransHelper::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            Log::info("New transaction created", [
                'code' => $transactionCode,
                'snap_token' => $snapToken,
            ]);
            return response()->json([
                'success' => true,
                'data' => [
                    'snap_token' => $snapToken,
                    'transaction_code' => $transactionCode,
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("Validation error in processCheckout: " . $e->getMessage(), [
                'errors' => $e->errors(),
            ]);
            return response()->json([
                'error' => 'Validasi gagal: ' . implode(', ', Arr::flatten($e->errors())),
            ], 422);
        } catch (\Exception $e) {
            Log::error("Checkout error: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Gagal memproses checkout: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function downloadByTransactionCode(Request $request, $transactionCode)
    {
        try {
            $transaction = Transaction::where('transaction_code', $transactionCode)
                ->where('user_id', Auth::id())
                ->where('status', 'paid')
                ->firstOrFail();

            $maxDownload = 3;
            if ($transaction->download_count >= $maxDownload) {
                return response()->json(['success' => false, 'message' => 'Batas maksimum download tercapai'], 403);
            }

            $product = $transaction->product;
            if (!$product->digital_file || !Storage::disk('public')->exists('digital_files/' . $product->digital_file)) {
                return response()->json(['success' => false, 'message' => 'File tidak ditemukan'], 404);
            }

            $hasAgreed = session("download_agreed_{$product->id}", false);
            if (!$hasAgreed) {
                return response()->json(['success' => false, 'message' => 'Persetujuan diperlukan'], 403);
            }

            $transaction->increment('download_count');
            session()->forget("download_agreed_{$product->id}");

            $fileUrl = asset('storage/digital_files/' . $product->digital_file);
            return response()->json(['success' => true, 'file_url' => $fileUrl]);
        } catch (\Exception $e) {
            Log::error("Error saat download file: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengunduh file: ' . $e->getMessage()], 500);
        }
    }

    public function downloadNow($productId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transaction = Transaction::where([
                'user_id' => $user->id,
                'product_id' => $productId,
                'status' => 'paid'
            ])->with('product')->first();

            if (!$transaction) {
                Log::warning("Transaksi tidak ditemukan", ['product_id' => $productId, 'user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            $maxDownload = 3;
            $downloadRemaining = max(0, $maxDownload - $transaction->download_count);

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction' => $transaction,
                    'download_remaining' => $downloadRemaining
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("Error saat downloadNow: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil info download.',
            ], 500);
        }
    }

    // 4. Accept Agreement - Setuju syarat & ketentuan
    public function acceptAgreement($productId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            Cache::put("download_agreed_{$productId}_user_" . $user->id, true, now()->addMinutes(5));

            return response()->json([
                'success' => true,
                'message' => 'Anda telah setuju dengan syarat dan ketentuan.'
            ]);
        } catch (\Exception $e) {
            Log::error("Error saat acceptAgreement: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui syarat dan ketentuan.',
            ], 500);
        }
    }

    // 5. Download File (berdasarkan productId)
    public function download1(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transaction = Transaction::where([
                'user_id' => $user->id,
                'product_id' => $productId,
                'status' => 'paid'
            ])->first();

            if (!$transaction) {
                Log::warning("Transaksi tidak ditemukan", ['product_id' => $productId, 'user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            $maxDownload = 3;
            if ($transaction->download_count >= $maxDownload) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda telah mencapai batas maksimum download.'
                ], 403);
            }

            $product = $transaction->product;
            if (!$product) {
                Log::warning("Produk tidak ditemukan untuk transaksi", ['transaction_id' => $transaction->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan.',
                ], 404);
            }

            if (!$product->digital_file || !Storage::disk('public')->exists('digital_files/' . $product->digital_file)) {
                Log::warning("File digital tidak ditemukan", ['product_id' => $productId, 'digital_file' => $product->digital_file]);
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan.'
                ], 404);
            }

            $hasAgreed = Cache::get("download_agreed_{$productId}_user_" . $user->id, false);
            if (!$hasAgreed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan setujui syarat dan ketentuan terlebih dahulu.',
                    'redirect_to_agree' => true
                ], 403);
            }

            $transaction->increment('download_count');
            Cache::forget("download_agreed_{$productId}_user_" . $user->id);

            $fileUrl = Storage::url('public/digital_files/' . $product->digital_file);

            Log::info("File berhasil diunduh", ['product_id' => $productId, 'file_url' => $fileUrl]);

            return response()->json([
                'success' => true,
                'file_url' => $fileUrl
            ]);
        } catch (\Exception $e) {
            Log::error("Error saat download: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengunduh file.',
            ], 500);
        }
    }

    // 6. Download File (berdasarkan transactionCode) - Endpoint baru untuk /transactions/{transactionCode}/download
    public function downloadFile(Request $request, $transactionCode)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning("User not authenticated for download", ['transaction_code' => $transactionCode]);
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transaction = Transaction::where('transaction_code', $transactionCode)
                ->where('user_id', $user->id)
                ->where('status', 'paid')
                ->first();

            if (!$transaction) {
                Log::warning("Transaction check failed", [
                    'transaction_code' => $transactionCode,
                    'user_id' => $user->id,
                    'transaction_exists' => Transaction::where('transaction_code', $transactionCode)->exists(),
                    'transaction_status' => Transaction::where('transaction_code', $transactionCode)->first()?->status,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan atau belum dibayar.',
                ], 404);
            }

            if ($transaction->download_count >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Batas maksimum download telah tercapai.',
                ], 403);
            }

            $product = $transaction->product;
            if (!$product || !$product->digital_file) {
                return response()->json([
                    'success' => false,
                    'message' => 'File digital tidak ditemukan.',
                ], 404);
            }

            $filePath = storage_path("app/public/digital_files/{$product->digital_file}");
            if (!file_exists($filePath)) {
                Log::error("File tidak ditemukan di server", ['file_path' => $filePath]);
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan di server.',
                ], 404);
            }

            $transaction->increment('download_count');
            Log::info("File diunduh", [
                'transaction_code' => $transactionCode,
                'download_count' => $transaction->download_count,
            ]);

            $fileUrl = url('storage/digital_files/' . $product->digital_file);

            return response()->json([
                'success' => true,
                'message' => 'File siap diunduh.',
                'file_url' => $fileUrl,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat mengunduh file: " . $e->getMessage(), [
                'transaction_code' => $transactionCode,
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunduh file: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 7. Riwayat Pesanan
    public function orderHistoried()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                Log::warning("Pengguna tidak terautentikasi untuk riwayat pesanan", ['user_id' => null]);
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transactions = Transaction::where('user_id', $user->id)
                ->where('status', 'paid')
                ->with('product')
                ->latest()
                ->get();

            Log::info("Mengambil riwayat pesanan", [
                'user_id' => $user->id,
                'transaction_count' => $transactions->count(),
                'transactions' => $transactions->toArray(),
            ]);

            $mappedTransactions = $transactions->map(function ($transaction) {
                $product = $transaction->product;
                if (!$product) {
                    Log::warning("Produk tidak ditemukan untuk transaksi", ['transaction_id' => $transaction->id]);
                    return null;
                }

                $hasReviewed = Review::where('product_id', $transaction->product_id)
                    ->where('user_id', $transaction->user_id)
                    ->exists();

                return [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'product_id' => $transaction->product_id,
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'thumbnail' => $product->thumbnail_url,
                    ],
                    'amount' => $transaction->amount,
                    'status' => $transaction->status,
                    'download_count' => $transaction->download_count,
                    'has_reviewed' => $hasReviewed,
                    'snap_token' => $transaction->snap_token,
                ];
            })->filter()->values();

            return response()->json([
                'success' => true,
                'data' => $mappedTransactions,
            ], 200);
        } catch (Exception $e) {
            Log::error("Error saat mengambil riwayat pesanan: " . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil riwayat: ' . $e->getMessage(),
            ], 500);
        }
    }

    // 8. Konfirmasi Pembayaran Manual
    public function confirmPayment(Request $request, $transactionId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transaction = Transaction::where('id', $transactionId)
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                Log::warning("Transaksi tidak ditemukan atau tidak valid", ['transaction_id' => $transactionId, 'user_id' => $user->id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan atau tidak valid.',
                ], 404);
            }

            $transaction->update(['status' => 'paid']);

            Log::info("Pembayaran berhasil dikonfirmasi", ['transaction_id' => $transactionId]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil! File siap diunduh.',
                'product_id' => $transaction->product_id
            ]);
        } catch (\Exception $e) {
            Log::error("Error saat confirmPayment: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pembayaran.'
            ], 500);
        }
    }

    // 9. Kirim Ulasan
    public function submitReview(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $product = Product::find($productId);
            if (!$product) {
                Log::warning("Produk tidak ditemukan", ['product_id' => $productId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan.',
                ], 404);
            }

            $existingReview = Review::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->exists();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah pernah memberikan ulasan untuk produk ini.'
                ], 400);
            }

            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            $review = Review::create([
                'product_id' => $productId,
                'user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            Log::info("Ulasan berhasil dikirim", ['review_id' => $review->id, 'product_id' => $productId]);

            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil dikirim!',
                'data' => $review
            ]);
        } catch (\Exception $e) {
            Log::error("Error saat submitReview: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim ulasan.',
            ], 500);
        }
    }

    public function cancelTransaction(Request $request, $transactionCode)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $transaction = Transaction::where('transaction_code', $transactionCode)
                ->where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan atau tidak dapat dibatalkan.',
                ], 404);
            }

            $transaction->update(['status' => 'cancelled']);
            Log::info("Transaksi dibatalkan", ['transaction_code' => $transactionCode]);

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan.',
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error saat membatalkan transaksi: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan transaksi.'
            ], 500);
        }
    }
}
