<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout($productId)
    {
        $product = Product::findOrFail($productId);

        // Cek apakah user sudah pernah beli produk ini
        $existing = Transaction::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('status', 'paid')
            ->first();

        if ($existing) {
            return redirect()->route('main.downloads')->with('info', 'Kamu sudah pernah membeli produk ini.');
        }

        // Simulasi pembelian langsung berhasil
        Transaction::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'transaction_code' => 'TRX-' . strtoupper(Str::random(10)),
            'amount' => $product->price,
            'status' => 'paid',
        ]);

        return redirect()->route('main.downloads')->with('success', 'Pembelian berhasil. File bisa diunduh!');
    }

    public function downloads()
    {
        $transactions = Transaction::with('product')
            ->where('user_id', Auth::id())
            ->where('status', 'paid')
            ->latest()
            ->get();

        return view('main.downloads', compact('transactions'));
    }

    public function download($productId)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->where('status', 'paid')
            ->firstOrFail();

        $product = $transaction->product;
        $path = storage_path('app/public/digital_files/' . $product->digital_file);

        return response()->download($path);
    }
}
