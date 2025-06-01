<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Seller;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminSaldoController extends Controller
{
    public function index()
    {
        $withdrawRequests = Withdraw::with('seller')
            ->latest()
            ->paginate(15);

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

    public function exportWithdrawalsPdf()
    {
        // Ambil semua penarikan seller
        $withdrawals = Withdraw::with('seller')->where('status', 'disetujui')->get();

        $totalWithdrawn = $withdrawals->sum('amount');

        // Load view PDF dari folder exports
        $pdf = Pdf::loadView('exports.laporan_penarikan_pdf', compact('withdrawals', 'totalWithdrawn'));

        return $pdf->download('laporan_penarikan_seller.pdf');
    }
}

