<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Seller;

class SaldoController extends Controller
{
    /**
     * Tampilkan halaman saldo seller beserta riwayat penarikan
     */
    public function index()
    {
        // Ambil user & seller
        $user = Auth::user();
        $seller = Seller::where('user_id', $user->id)->firstOrFail();

        // Cek apakah seller sudah isi rekening bank
        $sellerHasBank = !empty($seller->bank_name) && !empty($seller->bank_account);

        // Ambil saldo dari kolom balance
        $currentBalance = $seller->balance;

        // Riwayat penarikan
        $withdrawHistory = Withdraw::where('seller_id', $seller->id)
            ->latest()
            ->paginate(15);

        return view('seller.saldo.index', compact(
            'currentBalance',
            'withdrawHistory',
            'sellerHasBank',
            'seller'
        ));
    }

    /**
     * Proses permintaan penarikan saldo
     */
    public function tarikSaldo(Request $request)
    {
        // Ambil seller yang login
        $seller = Auth::user()->seller;

        // Validasi form
        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        if (!$seller) {
            return back()->with('error', 'Seller tidak ditemukan.');
        }

        if (empty($seller->bank_name) || empty($seller->bank_account)) {
            return back()->with('error', 'Silakan lengkapi informasi rekening bank terlebih dahulu.');
        }

        // Hitung current balance dari database
        $currentBalance = $seller->balance;

        if ($request->amount > $currentBalance) {
            return back()->with('error', 'Saldo tidak mencukupi untuk penarikan.');
        }

        // Simpan penarikan
        $seller->withdraws()->create([
            'amount' => $request->amount,
            'status' => 'pending',
            'bank_account' => $seller->bank_account,
            'bank_name' => $seller->bank_name,
        ]);

        return back()->with('success', 'Permintaan penarikan saldo berhasil dikirim.');
    }

    /**
     * Update informasi rekening bank seller
     */
    public function updateBank(Request $request)
    {
        // Ambil seller yang login
        $seller = Auth::user()->seller;

        // Validasi input
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account' => 'required|string|max:255',
        ]);

        // Update data bank
        $seller->update([
            'bank_name' => $request->bank_name,
            'bank_account' => $request->bank_account,
        ]);

        return back()->with('success', 'Informasi rekening berhasil diperbarui. Silakan lakukan penarikan.');
    }
}
