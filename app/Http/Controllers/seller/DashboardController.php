<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the seller dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Pastikan user sudah login
        $user = Auth::user();
        
        // Untuk sementara, bisa langsung menampilkan view dashboard
        return view('seller.dashboard');
    }
}