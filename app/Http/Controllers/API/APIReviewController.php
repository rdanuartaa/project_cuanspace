<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APIReviewController extends Controller
{
    public function getReviews(Request $request, $productId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pengguna tidak terautentikasi.',
                ], 401);
            }

            // Ambil ulasan untuk produk
            $reviews = Review::where('product_id', $productId)
                ->with(['user' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->select('id', 'product_id', 'user_id', 'rating', 'comment', 'created_at')
                ->get();

            // Cek apakah pengguna telah membeli produk
            $hasPurchased = Transaction::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->whereIn('status', ['disetujui', 'paid'])
                ->exists();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'reviews' => $reviews,
                    'has_purchased' => $hasPurchased,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil ulasan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
