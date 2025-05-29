@extends('layouts.admin')
@section('title', 'Kelola Saldo Seller')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css ">

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-4">Kelola Saldo Seller</h4>
                <a href="{{ route('admin.saldo.exportPdf') }}" class="btn btn-outline-success btn-sm">Ekspor ke PDF</a>
            </div>
            <!-- Ringkasan Saldo -->
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <div class="mb-4 text-center">
                        <span><strong>Total Saldo Platform:</strong></span>
                        <span class="text-lg font-bold">
                            Rp{{ number_format($totalSaldoPlatform, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
            <h5 class="mt-4">Permintaan Penarikan Saldo</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead class="table">
                        <tr>
                            <th>Seller</th>
                            <th>Bank</th> <!-- Tambahan kolom Bank -->
                            <th>Nomor Rekening</th> <!-- Tambahan kolom Nomor Rekening -->
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($withdrawRequests as $withdraw)
                            <tr>
                                <td>{{ $withdraw->seller->brand_name }}</td>
                                <td>{{ $withdraw->seller->bank_name ?? '-' }}</td> <!-- Tampilkan nama bank -->
                                <td>{{ $withdraw->seller->bank_account ?? '-' }}</td> <!-- Tampilkan nomor rekening -->
                                <td>Rp{{ number_format($withdraw->amount, 0, ',', '.') }}</td>
                                <td>{{ $withdraw->created_at->format('d M Y') }}</td>
                                <td>
                                    @if ($withdraw->status === 'pending')
                                        <span class="badge bg-warning text-white">{{ ucfirst($withdraw->status) }}</span>
                                    @elseif ($withdraw->status === 'disetujui')
                                        <span class="badge bg-success text-white">{{ ucfirst($withdraw->status) }}</span>
                                    @else
                                        <span class="badge bg-danger text-white">{{ ucfirst($withdraw->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($withdraw->status === 'pending')
                                        <form action="{{ route('admin.saldo.approve', $withdraw->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-outline-info btn-sm">Setujui</button>
                                        </form>
                                        <form action="{{ route('admin.saldo.reject', $withdraw->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-outline-danger btn-sm">Tolak</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Sudah Diproses</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Belum ada permintaan penarikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $withdrawRequests->links() }}
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <!-- Tabel Saldo Seller -->
            <h4 class="card-title mb-4">Saldo Aktif Masing-masing Seller</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead class="table">
                        <tr>
                            <th>Nama Seller</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sellerBalances as $seller)
                            <tr>
                                <td>{{ $seller->name }}</td>
                                <td>Rp{{ number_format($seller->balance, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Script Flatpickr -->
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
@endsection
