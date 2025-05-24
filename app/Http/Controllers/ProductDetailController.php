<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductDetailController extends Controller
{
    /**
     * Menampilkan detail produk untuk user biasa
     */
    public function showUserDetail($id)
{
    try {
        // Ambil produk utama
        $product = Product::with(['kategori', 'seller.user'])
            ->where('status', 'published')
            ->findOrFail($id);

        // Ambil produk terkait (misal: berdasarkan kategori)
        $relatedProducts = Product::where('kategori_id', $product->kategori_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'published')
            ->take(4)
            ->get();

        return view('main.detail_produk', compact('product', 'relatedProducts'));
    } catch (\Exception $e) {
        \Log::error('Error saat mengambil detail produk: ' . $e->getMessage());
        return redirect()->route('home')->with('error', 'Produk tidak ditemukan atau sedang tidak tersedia.');
    }
}
}
