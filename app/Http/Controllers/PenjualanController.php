<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori; // ✅ gunakan model yang benar

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['product', 'user'])
            ->whereHas('product', function ($q) use ($request) {
                $q->where('seller_id', Auth::id());

                if ($request->filled('kategori')) {
                    $q->where('category_id', $request->kategori);
                }
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        $kategoris = Kategori::all(); // ✅ pakai Kategori

        return view('seller.penjualan.index', compact('transactions', 'kategoris'));
    }

    public function show($id)
    {
        $transaction = Transaction::with(['user', 'product'])->findOrFail($id);

        if ($transaction->product->seller_id != Auth::id()) {
            abort(403);
        }

        return view('seller.penjualan.show', compact('transaction'));
    }
}
