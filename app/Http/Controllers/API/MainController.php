<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Review;
use App\Helpers\MidtransHelper;
use Midtrans\Snap;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    // 1. Checkout - Cek apakah sudah pernah beli
    public function checkout(Request $request, $productId)
    {
        $product = Product::with(['kategori', 'seller.user'])->findOrFail($productId);

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
                'status' => 'pending'
            ])->exists();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kamu sudah memiliki transaksi yang belum diselesaikan.'
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
                    'order_id' => $transaction->transaction_code,
                    'gross_amount' => $product->price,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
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

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'transaction_code' => $transactionCode
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Error saat checkout: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses checkout. Silakan coba lagi.'
            ], 500);
        }
    }

    // 3. Download Now - Info download
    public function downloadNow($productId)
    {
        $transaction = Transaction::where([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'status' => 'paid'
        ])->with('product')->firstOrFail();

        $maxDownload = 3;
        $downloadRemaining = max(0, $maxDownload - $transaction->download_count);

        return response()->json([
            'success' => true,
            'data' => [
                'transaction' => $transaction,
                'download_remaining' => $downloadRemaining
            ]
        ]);
    }

    // 4. Accept Agreement - Setuju syarat & ketentuan
    public function acceptAgreement($productId)
    {
        Cache::put("download_agreed_{$productId}_user_" . Auth::id(), true, now()->addMinutes(5));

        return response()->json([
            'success' => true,
            'message' => 'Anda telah setuju dengan syarat dan ketentuan.'
        ]);
    }

    // 5. Download File
    public function download(Request $request, $productId)
    {
        $transaction = Transaction::where([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'status' => 'paid'
        ])->firstOrFail();

        $maxDownload = 3;
        if ($transaction->download_count >= $maxDownload) {
            return response()->json([
                'success' => false,
                'message' => 'Anda telah mencapai batas maksimum download.'
            ], 403);
        }

        $product = $transaction->product;

        if (!$product->digital_file || !Storage::disk('public')->exists('digital_files/' . $product->digital_file)) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak ditemukan.'
            ], 404);
        }

        $hasAgreed = Cache::get("download_agreed_{$productId}_user_" . Auth::id(), false);
        if (!$hasAgreed) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan setujui syarat dan ketentuan terlebih dahulu.',
                'redirect_to_agree' => true
            ], 403);
        }

        $transaction->increment('download_count');
        Cache::forget("download_agreed_{$productId}_user_" . Auth::id());

        $fileUrl = Storage::disk('public')->url('digital_files/' . $product->digital_file);

        return response()->json([
            'success' => true,
            'file_url' => $fileUrl
        ]);
    }

    // 6. Riwayat Pesanan
    public function orderHistory()
    {
        $transactions = Transaction::with('product')
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    // 7. Konfirmasi Pembayaran Manual
    public function confirmPayment(Request $request, $transactionId)
    {
        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            $transaction->update(['status' => 'paid']);
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil! File siap diunduh.',
                'product_id' => $transaction->product_id
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi pembayaran.'
            ], 500);
        }
    }

    // 8. Kirim Ulasan
    public function submitReview(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $existingReview = Review::where('user_id', Auth::id())
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
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim!',
            'data' => $review
        ]);
    }
}
