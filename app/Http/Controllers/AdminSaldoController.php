<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Seller;

class AdminSaldoController extends Controller
{
    public function index()
    {
        $withdrawRequests = Withdraw::with('seller')
            ->latest()
            ->paginate(10);

        $sellerBalances = Seller::select('id', 'brand_name as name', 'balance')->get();

        $totalSaldoPlatform = $sellerBalances->sum('balance');

        return view('admin.saldo.index', compact('withdrawRequests', 'sellerBalances', 'totalSaldoPlatform'));
    }

    public function approve($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        if ($withdraw->status !== 'pending') {
            return back()->with('error', 'Penarikan sudah diproses.');
        }

        $withdraw->status = 'disetujui';
        $withdraw->save();

        return back()->with('success', 'Penarikan disetujui.');
    }

    public function reject($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        if ($withdraw->status !== 'pending') {
            return back()->with('error', 'Penarikan sudah diproses.');
        }

        // Kembalikan saldo ke seller
        $seller = $withdraw->seller;
        $seller->balance += $withdraw->amount;
        $seller->save();

        $withdraw->status = 'ditolak';
        $withdraw->save();

        return back()->with('success', 'Penarikan ditolak dan saldo dikembalikan.');
    }
}

