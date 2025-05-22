<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Kategori;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the transactions for the authenticated seller.
     */
    public function index(Request $request)
    {
        // Ambil semua transaksi yang terkait dengan produk dari seller yang sedang login
        $query = Transaction::with(['product', 'user'])
            ->whereHas('product', function ($q) use ($request) {
                // Pastikan produk tersebut milik seller yang login
                $q->where('seller_id', Auth::user()->seller->id);

                // Filter berdasarkan kategori jika dipilih
                if ($request->filled('kategori')) {
                    $q->where('kategori_id', $request->kategori);
                }
            });

        // Filter berdasarkan status transaksi
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $transactions = $query->latest()->paginate(10)->withQueryString();

        // Ambil semua kategori untuk dropdown filter
        $kategoris = Kategori::all();

        return view('seller.penjualan.index', compact('transactions', 'kategoris'));
    }

    /**
     * Show details of a specific transaction.
     */
    public function show($id)
    {
        // Cari transaksi beserta relasi product & user
        $transaction = Transaction::with(['user', 'product'])->findOrFail($id);

        // Cek apakah produk dari transaksi ini adalah milik seller yang login
        if ($transaction->product->seller_id != Auth::user()->seller->id) {
            abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
        }

        return view('seller.penjualan.show', compact('transaction'));
    }
}
