<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Seller (hanya penjual aktif)
        $totalSeller = Seller::where('status', 'active')->count();
        Log::info('Total Seller Active: ' . $totalSeller);

        // Total Produk (published & belum dihapus)
        $totalProduk = Product::where('status', 'published')
            ->whereNull('deleted_at')
            ->count();

        // Total Transaksi Berhasil
        $totalTransaksiBerhasil = Transaction::where('status', 'paid')->count();

        // Total Pengguna Terdaftar
        $totalPengguna = User::count();
        Log::info('Total Pengguna: ' . $totalPengguna);

        $totalSaldoPendingPlatform = Transaction::whereHas('product', function ($query) {
            $query->whereNotNull('seller_id');
        })
        ->where('transactions.status', 'pending')
        ->sum('transactions.amount');

        // Total Saldo Platform
        $totalSaldoPlatform = Seller::sum('balance');

        // Data untuk Seller Management
        $sellers = Seller::with('user')->get();

        // Top Seller (aktif, urut berdasarkan transaksi atau update terakhir)
        $topSellers = Seller::with('user')
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalSeller',
            'totalProduk',
            'totalTransaksiBerhasil',
            'totalSaldoPlatform',
            'totalSaldoPendingPlatform',
            'totalPengguna', // ⬅ Tambahkan variabel ini
            'sellers',
            'topSellers'
        ));
    }
}
