<?php

// app/Http/Controllers/Admin/SellerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Tampilkan daftar semua seller dengan filter dan sort.
     */
    public function index(Request $request)
    {
        $query = Seller::query();

        // Filter berdasarkan status
        if ($status = $request->query('status')) {
            if (in_array($status, ['pending', 'active', 'inactive'])) {
                $query->where('status', $status);
            }
        }

        // Sort berdasarkan waktu pembuatan
        $sort = $request->query('sort', 'latest');
        $query->orderBy('created_at', $sort === 'latest' ? 'desc' : 'asc');

        $sellers = $query->get();
        return view('admin.sellers.index', compact('sellers'));
    }

    /**
     * Verifikasi seller dan ubah status menjadi active.
     */
    public function verify(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'active';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller verified successfully!');
    }

    /**
     * Nonaktifkan seller dan ubah status menjadi inactive.
     */
    public function deactivate(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'inactive';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller deactivated successfully!');
    }

    /**
     * Kembalikan seller ke status pending.
     */
    public function setPending(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $seller->status = 'pending';
        $seller->save();

        return redirect()->route('admin.sellers.index')->with('status', 'Seller status set to pending!');
    }
}
