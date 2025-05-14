<?php

namespace App\Http\Controllers\API;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Fetch data for home screen
    public function index(Request $request)
    {
        // For this example, we'll return a list of users (excluding the authenticated user)
        $users = User::where('id', '!=', $request->user()->id)
                     ->select('id', 'name', 'email')
                     ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users,
        ], 200);
    }
}
