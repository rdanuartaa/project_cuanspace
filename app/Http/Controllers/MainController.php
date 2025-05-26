<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Kategori;
use App\Models\User;



class MainController extends Controller
{
    // Menampilkan halaman checkout
    public function checkout(Request $request, $productId)
    {
        $product = Product::with(['kategori', 'seller'])->findOrFail($productId);

        $existing = Transaction::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('status', 'paid')
            ->first();

        if ($existing) {
            return redirect()->route('main.downloads')
                ->with('info', 'Kamu sudah pernah membeli produk ini.');
        }

        return view('main.checkout', compact('product'));
    }

    // Memproses checkout: membuat transaksi baru
    public function processCheckout(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        // Cek apakah sudah ada transaksi pending sebelumnya
        $existing = Transaction::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return redirect()->route('main.checkout', $product->id)
                ->with('warning', 'Kamu sudah memiliki transaksi yang belum dikonfirmasi.');
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
            'amount' => $product->price,
            'status' => 'pending',
        ]);

        return redirect()->route('main.checkout', $product->id)
            ->with('success', 'Silakan lakukan pembayaran dan klik konfirmasi jika sudah.');
    }

    // Konfirmasi pembayaran
    public function confirmPayment(Request $request, $transactionId)
    {
        $transaction = Transaction::where('id', $transactionId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        // Simulasi konfirmasi pembayaran sukses
        $transaction->update(['status' => 'paid']);

        return redirect()->route('main.downloads')
            ->with('success', 'Pembayaran berhasil dikonfirmasi. Produk dapat diunduh!');
    }

    // Menampilkan daftar produk yang bisa diunduh setelah dibayar
    public function downloads(Request $request)
    {
        $transactions = Transaction::with('product')
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view('main.downloads', compact('transactions'));
    }

    // Menangani proses download file
    public function download(Request $request, $productId)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('status', 'paid')
            ->firstOrFail();

        $product = $transaction->product;

        if ($product->digital_file && \Storage::disk('public')->exists('digital_files/' . $product->digital_file)) {
            return response()->download(storage_path('app/public/digital_files/' . $product->digital_file));
        }

        return back()->with('error', 'File tidak ditemukan.');
    }

}
