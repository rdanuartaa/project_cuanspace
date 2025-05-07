<?php

// app/Http/Controllers/Seller/DashboardController.php

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
        $user = Auth::user();
        $seller = $user->seller;

        return view('seller.dashboard', compact('seller'));
    }
}
