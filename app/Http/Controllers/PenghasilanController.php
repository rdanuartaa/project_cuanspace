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
    public function index(Request $request)
    {
        $sellerId = Auth::user()->id;

        // Base query untuk transaksi berhasil
        $baseQuery = Transaction::whereHas('product', function ($query) use ($sellerId) {
                $query->where('seller_id', $sellerId);
            })
            ->whereIn('status', ['berhasil', 'paid'])
            ->with(['product', 'user']);

        // Filter berdasarkan tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Pagination
        $penghasilan = $baseQuery->latest()->paginate(10);

        // Hitung total penghasilan sesuai filter
        $total = $baseQuery->sum('amount');

        // Hitung pending (tidak terpengaruh filter tanggal)
        $pending = Transaction::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
            ->where('status', 'pending')
            ->sum('amount');

        // Minggu ini (tetap gunakan minggu ini meskipun filter aktif)
        $mingguIni = Transaction::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
            ->whereIn('status', ['berhasil', 'paid'])
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');

        // Bulan ini (tetap gunakan bulan ini meskipun filter aktif)
        $bulanIni = Transaction::whereHas('product', fn($q) => $q->where('seller_id', $sellerId))
            ->whereIn('status', ['berhasil', 'paid'])
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
    public function export(Request $request)
{
    $sellerId = Auth::user()->id;

    // Salin baseQuery dari index()
    $baseQuery = Transaction::whereHas('product', function ($q) use ($sellerId) {
        $q->where('seller_id', $sellerId);
    })->whereIn('status', ['berhasil', 'paid']);

    // Filter tanggal jika dipilih
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Ambil semua data tanpa pagination
    $transactions = $baseQuery->with(['product', 'user'])->get();

    // Debugging: Cek apakah ada data
    \Log::info("Export Transactions Count", ['count' => $transactions->count()]);

    return Excel::download(new PenghasilanExport($transactions), 'laporan_penghasilan.xlsx');
}
}
