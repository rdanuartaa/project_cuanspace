<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:sellers,id', // Validasi ke sellers
            'kategori_id' => 'required|exists:kategoris,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'digital_file' => 'required|file|mimes:pdf,zip,doc,docx|max:10240',
            'status' => 'required|in:draft,published,archived',
        ]);
        try {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $digitalFilePath = $request->file('digital_file')->store('files', 'public');
            Log::info('File disimpan', [
                'thumbnail' => $thumbnailPath,
                'digital_file' => $digitalFilePath
            ]);
            $product = Product::create([
                'seller_id' => $request->seller_id,
                'kategori_id' => $request->kategori_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'thumbnail' => basename($thumbnailPath),
                'digital_file' => $digitalFilePath,
                'status' => $request->status,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dibuat.',
                'data' => $product->load('kategori'),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Gagal membuat produk', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            $query = Product::where('status', 'published')
                ->select('id', 'seller_id', 'kategori_id', 'name', 'description', 'price', 'thumbnail', 'digital_file', 'status')
                ->with('kategori:id,nama_kategori')
                ->with('reviews'); // Sertakan relasi reviews

            // Filter produk agar tidak menampilkan produk milik seller yang login
            if ($user->role === 'seller') {
                $seller = Seller::where('user_id', $user->id)->first();
                if ($seller) {
                    $query->where('seller_id', '!=', $seller->id);
                }
            }

            $products = $query->get()->map(function ($product) {
                $product->thumbnail = $product->thumbnail_url;

                // Hitung rata-rata rating dan jumlah ulasan
                $reviews = $product->reviews;
                $reviewCount = $reviews->count();
                $averageRating = $reviewCount > 0
                    ? $reviews->avg('rating')
                    : 0.0;

                $product->average_rating = $averageRating;
                $product->review_count = $reviewCount;

                return $product;
            });

            return response()->json([
                'success' => true,
                'data' => $products,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Kesalahan saat mengambil produk: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil produk: ' . $e->getMessage(),
            ], 500);
        }
    }
}
