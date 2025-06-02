<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Review;
use App\Helpers\MidtransHelper;
use Exception;
use Midtrans\Snap;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    // Menampilkan halaman checkout
    public function checkout(Request $request, $productId)
    {
        $product = Product::with(['kategori', 'seller.user'])->findOrFail($productId);

        // Cek apakah sudah pernah beli produk ini
        $existingPaid = Transaction::where([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'status' => 'paid'
        ])->first();

        if ($existingPaid) {
            return redirect()->route('main.download.now', ['productId' => $product->id])
                ->with('info', 'Kamu sudah pernah membeli produk ini.');
        }

        return view('main.checkout', compact('product'));
    }

    // Memproses checkout dan buat transaksi + Midtrans
    public function processCheckout(Request $request, $productId)
    {
        try {
            // Validasi input
            $request->validate([
                'email' => 'required|email',
                'agree' => 'accepted',
            ]);

            $product = Product::findOrFail($productId);

            // Cek transaksi pending sebelumnya
            $existing = Transaction::where([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'status' => 'pending'
            ])->first();

            if ($existing) {
                return response()->json(['error' => 'Kamu sudah memiliki transaksi yang belum diselesaikan.'], 400);
            }

            // Buat kode transaksi & simpan
            $transactionCode = 'CSP-' . strtoupper(Str::random(8));
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'transaction_code' => $transactionCode,
                'amount' => $product->price,
                'status' => 'pending',
            ]);

            // Siapkan parameter Midtrans
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

            // Generate Snap Token
            $snapToken = MidtransHelper::getSnapToken($params);
            $transaction->update(['snap_token' => $snapToken]);

            // Return snap token sebagai JSON
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            Log::error("Error saat checkout: " . $e->getMessage());
            return response()->json(['error' => 'Gagal memproses checkout. Silakan coba lagi.'], 500);
        }
    }

    // Halaman detail download
    public function downloadNow($productId)
    {
        $transaction = Transaction::where([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'status' => 'paid'
        ])->with('product')->firstOrFail();

        $product = $transaction->product;
        $maxDownload = 3;
        $downloadRemaining = max(0, $maxDownload - $transaction->download_count);

        // Hapus notifikasi setelah ditampilkan
        $justAgreed = session('just_agreed', false);
        session()->forget('just_agreed');

        return view('main.download_now', compact('product', 'transaction', 'downloadRemaining', 'justAgreed'));
    }

    // Proses download file
    public function download(Request $request, $productId)
    {
        $transaction = Transaction::where([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'status' => 'paid'
        ])->firstOrFail();

        $maxDownload = 3;

        if ($transaction->download_count >= $maxDownload) {
            return back()->with('error', 'Anda telah mencapai batas maksimum download.');
        }

        $product = $transaction->product;

        if (!$product->digital_file || !Storage::disk('public')->exists('digital_files/' . $product->digital_file)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // Cek apakah user sudah setuju
        $hasAgreed = session("download_agreed_{$productId}", false);
        if (!$hasAgreed) {
            return redirect()->route('main.download.agree', ['productId' => $productId]);
        }

        // Tambahkan jumlah download
        $transaction->increment('download_count');

        // Reset sesi setuju (opsional)
        session()->forget("download_agreed_{$productId}");

        // Kirim file sebagai download
        $filePath = storage_path('app/public/digital_files/' . $product->digital_file);
        return response()->download($filePath);
    }

    // Tampilkan halaman syarat & ketentuan
    public function showAgreePage($productId)
    {
        $transaction = Transaction::where([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'status' => 'paid'
        ])->firstOrFail();

        $product = $transaction->product;

        return view('main.agree_download', compact('product'));
    }

    // Terima persetujuan dan arahkan ke download
    public function acceptAgreement($productId)
    {
        // Simpan status persetujuan di session
        session(["download_agreed_{$productId}" => true]);

        // Redirect langsung ke halaman download
        return redirect()->route('main.download', ['productId' => $productId]);
    }

    // Riwayat pesanan
    public function orderHistory()
    {
        $transactions = Transaction::with('product')
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view('main.order_history', compact('transactions'));
    }

    // Konfirmasi pembayaran manual
    public function confirmPayment(Request $request, $transactionId)
    {
        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            $transaction->update(['status' => 'paid']);
            return redirect()->route('main.download.now', ['productId' => $transaction->product_id])
                ->with('success', 'Pembayaran berhasil! File siap diunduh.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mengkonfirmasi pembayaran.');
        }
    }

    // Kirim ulasan
    public function submitReview(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $existingReview = Review::where('user_id', Auth::id())
                                ->where('product_id', $productId)
                                ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah pernah memberikan ulasan untuk produk ini.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        return redirect()->back()->with('success', 'Ulasan berhasil dikirim!');
    }

}
