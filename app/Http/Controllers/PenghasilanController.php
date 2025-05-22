<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenghasilanExport;

class PenghasilanController extends Controller
{
    /**
     * Show list of transactions and earnings for the authenticated seller.
     */
    public function index(Request $request)
    {
        // Ambil ID seller dari user yang login
        $sellerId = Auth::user()->seller->id ?? Auth::user()->id;

        // Base query untuk transaksi berhasil (paid / berhasil)
        $baseQuery = Transaction::whereHas('product', function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        })->whereIn('status', ['paid', 'berhasil'])->with(['product', 'user']);

        // Filter tanggal jika diisi
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Pagination
        $penghasilan = $baseQuery->latest()->paginate(10);

        // Total penghasilan sesuai filter
        $total = $baseQuery->sum('amount');

        // Transaksi pending (tidak terpengaruh filter tanggal)
        $pending = Transaction::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->where('status', 'pending')->sum('amount');

        // Minggu ini (tetap gunakan minggu ini meskipun filter aktif)
        $mingguIni = Transaction::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->whereIn('status', ['paid', 'berhasil'])
          ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
          ->sum('amount');

        // Bulan ini (tetap gunakan bulan ini meskipun filter aktif)
        $bulanIni = Transaction::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->whereIn('status', ['paid', 'berhasil'])
          ->whereMonth('created_at', now()->month)
          ->whereYear('created_at', now()->year)
          ->sum('amount');

        return view('seller.penghasilan.index', compact(
            'penghasilan',
            'total',
            'pending',
            'mingguIni',
            'bulanIni'
        ));
    }

    /**
     * Export laporan penghasilan ke Excel
     */
    public function export(Request $request)
{
    // Ambil seller ID dari user yang login
    $user = Auth::user();
    $sellerId = $user->seller->id ?? $user->id;

    // Validasi seller
    if (!$sellerId || !\App\Models\Seller::find($sellerId)) {
        abort(403, 'Anda tidak memiliki akses');
    }

    Log::info("Seller ID untuk export: " . $sellerId);

    // Base query
    $baseQuery = Transaction::whereHas('product', function ($query) use ($sellerId) {
        $query->where('seller_id', $sellerId);
    })->whereIn('status', ['paid', 'berhasil']);

    // Filter tanggal jika ada
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
        $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Ambil data
    $transactions = $baseQuery->with(['product', 'user'])->get();

    // Debug output
    Log::info("Jumlah transaksi ditemukan: " . $transactions->count());
    Log::info("Contoh transaksi:", $transactions->take(2)->map(function ($t) {
        return [
            'kode' => $t->transaction_code,
            'produk' => $t->product->name,
            'seller_id' => $t->product->seller_id,
        ];
    })->toArray());

    return Excel::download(new PenghasilanExport($transactions), 'laporan_penghasilan.xlsx');
}
}
