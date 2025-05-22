<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaction::with(['user', 'product.seller'])
            ->latest()
            ->paginate(15);

        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function show($id)
    {
        $detail = Transaction::with(['user', 'product.seller'])->findOrFail($id);

        return view('admin.transaksi.show', compact('detail'));
    }
}
