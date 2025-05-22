<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\About;

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
}
