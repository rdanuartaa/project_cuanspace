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
    public function processCheckout(Request $request, $id)
{
    $request->validate([
        'email' => 'required|email',
        'agree' => 'accepted',
    ]);

    $product = Product::findOrFail($id);

    // Simpan transaksi ke database (status = pending)
    $transaction = \App\Models\Transaction::create([
        'user_id' => auth()->id(),
        'product_id' => $product->id,
        'email' => $request->email,
        'note' => $request->note,
        'status' => 'pending',
        'total_price' => $product->price,
    ]);

    // Buat payload Snap Midtrans
    $params = [
        'transaction_details' => [
            'order_id' => 'ORDER-' . $transaction->id . '-' . time(),
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
            'name' => $product->name
        ]],
    ];

    // Simpan order_id ke transaksi
    $transaction->update(['midtrans_order_id' => $params['transaction_details']['order_id']]);

    // Ambil Snap token dari Midtrans
    $snapToken = Snap::getSnapToken($params);

    return view('main.payment', compact('snapToken'));
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
