<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use App\Models\Review; // Tambahkan model Review

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter
        $kategoris = Kategori::all();

        // Query untuk produk yang published dan seller aktif
        $query = Product::where('status', 'published')
            ->with(['kategori', 'seller', 'reviews']) // Eager load reviews
            ->whereHas('seller', function($q) {
                $q->where('status', 'active');
            });

        // Filter berdasarkan kategori jika ada
        if ($request->filled('kategori') && $request->kategori != 'all') {
            $query->where('kategori_id', $request->kategori);
        }

        // Pagination
        $products = $query->latest()->paginate(12);

        // Hitung rating bintang dinamis untuk setiap produk
        $products->getCollection()->transform(function ($product) {
            $averageRating = $product->reviews->avg('rating') ?? 0;
            $fullStars = floor($averageRating);
            $halfStar = $averageRating - $fullStars >= 0.5;

            // Tambahkan atribut custom ke objek produk
            $product->average_rating = round($averageRating, 1);
            $product->full_stars = $fullStars;
            $product->has_half_star = $halfStar;

            return $product;
        });

        return view('main.home', compact('products', 'kategoris'));
    }
}
