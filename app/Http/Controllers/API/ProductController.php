<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:users,id',
            'kategori_id' => 'required|exists:kategoris,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Maks 2MB
            'digital_file' => 'required|file|mimes:pdf,zip,doc,docx|max:10240', // Maks 10MB
            'status' => 'required|in:draft,published,archived',
        ]);

        try {
            // Simpan thumbnail
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');

            // Simpan digital file
            $digitalFilePath = $request->file('digital_file')->store('files', 'public');

            // Buat produk baru
            $product = Product::create([
                'seller_id' => $request->seller_id,
                'kategori_id' => $request->kategori_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'thumbnail' => $thumbnailPath,
                'digital_file' => $digitalFilePath,
                'status' => $request->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dibuat.',
                'data' => $product->load('kategori'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        $products = Product::where('status', 'published')
            ->select('id', 'seller_id', 'kategori_id', 'name', 'description', 'price', 'thumbnail', 'digital_file', 'status')
            ->with('kategori:id,nama_kategori')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}
