@extends('layouts.seller')
@section('title', 'Penghasilan Saya')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css ">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Penghasilan Saya</h4>

            <!-- Total Penghasilan -->
            @php
                $total = $penghasilan->sum('amount');
            @endphp

            <div class="row">
                <div class="col-sm-12">
                    <div class="mb-2 d-flex align-items-center justify-content-between">
                        <div class="text-center">
                            <strong class="font-semibold">Pending:</strong>
                            <span
                                class="text-green-600 font-bold text-lg">Rp{{ number_format($pending, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-center">
                            <strong class="font-semibold">Minggu Ini:</strong>
                            <span
                                class="text-green-600 font-bold text-lg">Rp{{ number_format($mingguIni, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-center">
                            <strong class="font-semibold">Bulan Ini:</strong>
                            <span
                                class="text-green-600 font-bold text-lg">Rp{{ number_format($bulanIni, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-center">
                            <strong class="font-semibold">Total Penghasilan:</strong>
                            <span class="text-green-600 font-bold text-lg">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <form id="filterForm" method="GET" action="{{ route('seller.penghasilan.index') }}" class="row my-2 g-2">
                <div class="col-md-3 ">
                    <label for="date_range" class="form-label ">Pilih Rentang Tanggal</label>
                    <input type="text" id="date_range" class="form-control text-center"
                        placeholder="Pilih tanggal awal - akhir" readonly>
                </div>
                <!-- Hidden Inputs -->
                <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">
            </form>
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('seller.penghasilan.export', request()->only(['start_date', 'end_date'])) }}"
                        class="btn btn-success text-white ms-auto">
                        <i class="mdi mdi-download"></i> Unduh Laporan
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Pembeli</th>
                            <th>Tanggal Pembayaran</th>
                            <th>Status</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penghasilan as $item)
                            <tr>
                                <td class="flex items-center gap-2">
                                    @if ($item->product && $item->product->thumbnail)
                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="Foto Produk"
                                            class="w-10 h-10 rounded object-cover">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center text-sm text-gray-500">
                                            N/A
                                        </div>
                                    @endif
                                    <span>{{ $item->product->name ?? '-' }}</span>
                                </td>
                                <td>{{ $item->user->name ?? '-' }}</td>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    @if ($item->status === 'paid' || $item->status === 'berhasil')
                                        <span class="text-green-600 font-medium">Pembayaran Berhasil</span>
                                    @else
                                        <span class="text-gray-500">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                                <td>Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada penghasilan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $penghasilan->links() }}
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/flatpickr "></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: ["{{ request('start_date') }}", "{{ request('end_date') }}"],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const startDate = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const endDate = flatpickr.formatDate(selectedDates[1], "Y-m-d");

                    document.getElementById('start_date').value = startDate;
                    document.getElementById('end_date').value = endDate;

                    // Submit form secara otomatis
                    document.getElementById('filterForm').submit();
                }
            }
        });
    });
</script>
