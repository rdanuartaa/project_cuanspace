<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Seller (hanya penjual aktif)
        $totalSeller = Seller::where('status', 'active')->count();
        Log::info('Total Seller Active: ' . $totalSeller);

        // Total Produk (hanya produk yang dipublikasikan dan belum dihapus)
        $totalProduk = Product::where('status', 'published')
            ->whereNull('deleted_at')
            ->count();

        // Total Transaksi Berhasil (hanya transaksi yang sudah dibayar)
        $totalTransaksiBerhasil = Transaction::where('status', 'paid')->count();

        // Total Saldo Platform (menggunakan sum dari balance penjual)
        $totalSaldoPlatform = Seller::sum('balance');

        // Data untuk Seller Management (semua penjual, termasuk relasi dengan user)
        $sellers = Seller::with('user')->get();

        // Data untuk Top Seller (penjual aktif, diurutkan berdasarkan updated_at)
        $topSellers = Seller::with('user')
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get();

        // Kirim variabel yang diperlukan ke view
        return view('admin.dashboard', compact(
            'totalSeller',
            'totalProduk',
            'totalTransaksiBerhasil',
            'totalSaldoPlatform',
            'sellers',
            'topSellers'
        ));
    }
}
