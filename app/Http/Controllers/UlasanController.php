<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
{
    // Query dasar untuk ulasan milik seller
    $query = Review::with(['product'])
        ->whereHas('product', function ($q) {
            $q->where('seller_id', Auth::id());
        });

    // Filter berdasarkan rating jika ada
    if ($request->filled('rating')) {
        $rating = (int)$request->input('rating');
        if ($rating >= 1 && $rating <= 5) {
            $query->where('rating', $rating);
        }
    }

    // Ambil data reviews dengan pagination
    $reviews = $query->latest()->paginate(10);

    // Hitung total rating dan jumlah ulasan
    $totalRating = $reviews->sum('rating');
    $jumlahReview = $reviews->count();

    // Hitung rata-rata rating toko
    $averageRating = $jumlahReview > 0 ? round($totalRating / $jumlahReview, 2) : 0;

    return view('seller.ulasan.index', compact('reviews', 'averageRating'));
}

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        // Cek apakah user sudah pernah review produk ini
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($existingReview) {
            return back()->withErrors(['error' => 'Anda sudah pernah memberikan ulasan untuk produk ini.']);
        }

        // Buat review baru
        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan.');
    }
}
