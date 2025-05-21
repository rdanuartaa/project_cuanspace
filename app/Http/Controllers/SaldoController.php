<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Withdraw;
use Carbon\Carbon;

class SaldoController extends Controller
{
    public function index()
    {
        $sellerId = Auth::user()->id;

        // Hitung total penghasilan berhasil
        $totalIncome = Transaction::whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->whereIn('status', ['berhasil', 'paid'])
            ->sum('amount');

        // Hitung total yang sudah ditarik
        $totalWithdrawn = Withdraw::where('seller_id', $sellerId)
            ->where('status', 'disetujui') // status bisa disesuaikan
            ->sum('amount');

        // Hitung saldo saat ini
        $currentBalance = $totalIncome - $totalWithdrawn;

        // Riwayat penarikan saldo
        $withdrawHistory = Withdraw::where('seller_id', $sellerId)
            ->latest()
            ->paginate(10);

        return view('seller.saldo.index', compact('currentBalance', 'withdrawHistory'));
    }

    public function tarikSaldo(Request $request)
{
    $sellerId = Auth::user()->id;

    // Validasi form
    $request->validate([
        'amount' => 'required|numeric|min:10000',
    ]);

    // Hitung saldo saat ini
    $totalIncome = Transaction::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })
        ->whereIn('status', ['berhasil', 'paid'])
        ->sum('amount');

    $totalWithdrawn = Withdraw::where('seller_id', $sellerId)
        ->where('status', 'disetujui')
        ->sum('amount');

    $currentBalance = $totalIncome - $totalWithdrawn;

    // Cek apakah saldo cukup
    if ($request->amount > $currentBalance) {
        return back()->with('error', 'Saldo tidak mencukupi untuk penarikan.');
    }

    // Simpan data penarikan
    Withdraw::create([
        'seller_id' => $sellerId,
        'amount' => $request->amount,
        'status' => 'pending',
    ]);

    return back()->with('success', 'Permintaan penarikan saldo berhasil dikirim.');
}

}
