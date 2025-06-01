<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\About;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

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

    public function showAbout()
    {
        $about = About::where('status', 'published')->first();
        return view('main.about', compact('about'));
    }

    public function products(Request $request)
    {
        $user = $request->user();

        $query = Product::select('id', 'seller_id', 'kategori_id', 'name', 'description', 'price', 'thumbnail', 'digital_file', 'status')
                        ->where('status', 'published')
                        ->withActiveSeller()
                        ->with('kategori');

        if ($user->role === 'seller') {
            $seller = Seller::where('user_id', $user->id)->first();
            if ($seller) {
                $query->where('seller_id', '!=', $seller->id);
            }
        }

        $products = $query->get()->map(function ($product) {
            $product->thumbnail = $product->thumbnail_url;
            return $product;
        });

        return response()->json([
            'status' => 'success',
            'data' => $products,
        ]);
    }

    public function trending(Request $request)
    {
        $sortBy = $request->query('sort_by', 'purchases');
        $limit = $request->query('limit', 10);
        $user = $request->user();

        if ($sortBy !== 'purchases') {
            return response()->json([
                'status' => 'error',
                'message' => 'Sort by tidak valid. Hanya "purchases" yang didukung.',
            ], 400);
        }

        $query = Product::select(
            'products.id',
            'products.seller_id',
            'products.kategori_id',
            'products.name',
            'products.description',
            'products.price',
            'products.thumbnail',
            'products.digital_file',
            'products.status'
        )
        ->where('products.status', 'published')
        ->withActiveSeller()
        ->with('kategori')
        ->leftJoin('transactions', function ($join) {
            $join->on('products.id', '=', 'transactions.product_id')
                 ->where('transactions.status', 'paid');
        })
        ->groupBy(
            'products.id',
            'products.seller_id',
            'products.kategori_id',
            'products.name',
            'products.description',
            'products.price',
            'products.thumbnail',
            'products.digital_file',
            'products.status'
        )
        ->orderByRaw('COUNT(transactions.id) DESC')
        ->addSelect(DB::raw('COUNT(transactions.id) as transaction_count'))
        ->take($limit);

        if ($user->role === 'seller') {
            $seller = Seller::where('user_id', $user->id)->first();
            if ($seller) {
                $query->where('products.seller_id', '!=', $seller->id);
            }
        }

        $products = $query->get()->map(function ($product) {
            $product->thumbnail = $product->thumbnail_url;
            return $product;
        });

        return response()->json([
            'status' => 'success',
            'data' => $products,
            'message' => 'Produk trending berhasil diambil',
        ]);
    }
}
