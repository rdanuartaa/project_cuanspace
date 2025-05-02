<?php

// app/Http/Controllers/Admin/SellerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Tampilkan daftar seller dengan status pending.
     */
    public function index()
    {
        $sellers = Seller::where('status', 'pending')->get();
        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Verifikasi seller dan ubah status menjadi active.
     */
    public function verify($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'active';  // Ubah status seller menjadi active
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller verified successfully!');
    }
}

