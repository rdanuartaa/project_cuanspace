<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\About;
use App\Models\Product;
use App\Models\Seller;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Fetch users except current
    public function index(Request $request)
    {
        $users = User::where('id', '!=', $request->user()->id)
            ->select('id', 'name', 'email')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ]);
    }

    // Show about data (for front end)
    public function showAbout()
    {
        $about = About::where('status', 'published')->first();
        return view('main.about', compact('about'));
    }

    // Fetch trending products

    public function products(Request $request)
{
    $user = $request->user(); // Pengguna yang sedang login

    $query = Product::select('id', 'seller_id', 'kategori_id', 'name', 'description', 'price', 'thumbnail', 'digital_file', 'status', 'view_count', 'purchase_count')
                    ->where('status', 'published') // Hanya produk yang published
                    ->withActiveSeller() // Hanya produk dari seller aktif
                    ->with('kategori'); // Sertakan relasi kategori

    // Jika pengguna adalah seller, jangan tampilkan produk mereka sendiri
    if ($user->role === 'seller') {
        $seller = Seller::where('user_id', $user->id)->first();
        if ($seller) {
            $query->where('seller_id', '!=', $seller->id);
        }
    }

    $products = $query->get();

    return response()->json([
        'status' => 'success',
        'data' => $products,
    ]);
}
    public function trending(Request $request)
{
    $sortBy = $request->query('sort_by', 'views');
    $limit = $request->query('limit', 10); // Default limit to 10
    $user = $request->user(); // Pengguna yang sedang login

    $query = Product::select('id', 'seller_id', 'kategori_id', 'name', 'description', 'price', 'thumbnail', 'digital_file', 'status', 'view_count', 'purchase_count')
                    ->where('status', 'published') // Hanya produk yang published
                    ->withActiveSeller() // Hanya produk dari seller aktif
                    ->with('kategori'); // Sertakan relasi kategori

    // Jika pengguna adalah seller, jangan tampilkan produk mereka sendiri
    if ($user->role === 'seller') {
        $seller = Seller::where('user_id', $user->id)->first();
        if ($seller) {
            $query->where('seller_id', '!=', $seller->id);
        }
    }

    // Urutkan berdasarkan parameter sortBy
    if ($sortBy === 'purchases') {
        $query->orderBy('purchase_count', 'desc');
    } else {
        $query->orderBy('view_count', 'desc');
    }

    $products = $query->take($limit)->get();

    return response()->json([
        'status' => 'success',
        'data' => $products,


    ]);
}
}
