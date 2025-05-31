<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Kategori;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Menampilkan daftar produk seperti di Web HomeController@index
     */
    public function index(Request $request)
    {
        try {
            // Ambil semua kategori untuk filter
            $kategoris = Kategori::select('id', 'nama_kategori')->get();

            // Query dasar: hanya produk published dan seller aktif
            $query = Product::where('status', 'published')
                ->with(['kategori', 'seller.user'])
                ->whereHas('seller', function ($q) {
                    $q->where('status', 'active');
                });

            // 🔍 Tambahkan pencarian berdasarkan nama produk
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Filter berdasarkan kategori jika ada
            if ($request->filled('kategori') && $request->kategori != 'all') {
                $query->where('kategori_id', $request->kategori);
            }

            // Pagination
            $perPage = $request->input('per_page', 12); // Default 12 per halaman
            $products = $query->latest()->paginate($perPage);

            // Hitung rating bintang dinamis untuk setiap produk
            $products->getCollection()->transform(function ($product) {
                $averageRating = $product->reviews->avg('rating') ?? 0;
                $fullStars = floor($averageRating);
                $halfStar = $averageRating - $fullStars >= 0.5;
                $product->review_count = $product->reviews->count();

                // Tambahkan atribut custom ke objek produk
                $product->average_rating = round($averageRating, 1);
                $product->review_count = $product->reviews->count();
                $product->full_stars = $fullStars;
                $product->has_half_star = $halfStar;

                return $product;
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'products' => $products,
                    'kategoris' => $kategoris,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
