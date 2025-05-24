<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Kategori;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter
        $kategoris = Kategori::all();
        
        // Query untuk produk yang published
        $query = Product::where('status', 'published')
            ->with(['kategori', 'seller'])
            ->whereHas('seller', function($q) {
                $q->where('status', 'active');
            });
        
        // Filter berdasarkan kategori jika ada
        if ($request->filled('kategori') && $request->kategori != 'all') {
            $query->where('kategori_id', $request->kategori);
        }
        
        // Pagination
        $products = $query->latest()->paginate(12);
        
        return view('main.home', compact('products', 'kategoris'));
    }
}