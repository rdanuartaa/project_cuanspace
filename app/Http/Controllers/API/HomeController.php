<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\About;
use App\Models\Product;

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
    public function trending(Request $request)
    {
        $sortBy = $request->query('sort_by', 'views'); // Default to 'views'
        $limit = $request->query('limit', 10); // Default limit to 10

        $query = Product::select('id', 'name', 'image', 'view_count', 'purchase_count');

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