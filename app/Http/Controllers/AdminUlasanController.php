<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminUlasanController extends Controller
{
    /**
     * Tampilkan semua ulasan produk dari seluruh seller.
     */
    public function index(Request $request)
{
    // Ambil semua ulasan dengan relasi product dan user
    $query = Review::with(['product.seller', 'user']);

    // Filter berdasarkan rating jika ada
    if ($request->filled('rating')) {
        $rating = (int)$request->input('rating');
        if ($rating >= 1 && $rating <= 5) {
            $query->where('rating', $rating);
        }
    }

    // Filter berdasarkan seller_id (opsional)
    if ($request->filled('seller_id')) {
        $sellerId = $request->input('seller_id');
        $query->whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        });
    }

    // Filter berdasarkan product_id (opsional)
    if ($request->filled('product_id')) {
        $productId = $request->input('product_id');
        $query->where('product_id', $productId);
    }

    // Pagination
    $reviews = $query->latest()->paginate(15);

    // Tambahkan data sellers dan products ke view
    $sellers = Seller::all();
    $products = Product::all();

    return view('admin.ulasan.index', compact('reviews', 'sellers', 'products'));
}

    /**
     * Hapus ulasan yang dipilih.
     */
    public function destroy($id)
    {
        $review = Review::with(['product'])->findOrFail($id);

        try {
            $review->delete();
            return back()->with('success', 'Ulasan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Gagal menghapus ulasan: " . $e->getMessage());
            return back()->with('error', 'Gagal menghapus ulasan. Silakan coba lagi.');
        }
    }
}
