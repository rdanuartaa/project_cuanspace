@extends('layouts.seller')
@section('title', 'Daftar Penjualan')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Penjualan</h4>

                @if (session('success'))
                    <div class="alert alert-success rounded fade show" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger rounded fade show" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <form method="GET" action="{{ route('seller.penjualan.index') }}" class="row mb-4">
                    <div class="col-md-3">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="table">
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Produk</th>
                                <th>Pembeli</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $i => $trx)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $trx->transaction_code }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($trx->product)
                                                <img src="{{ $trx->product->thumbnail_url }}"
                                                    alt="{{ $trx->product->name }}"
                                                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                            @else
                                                <div
                                                    style="width: 40px; height: 40px; background-color: #f0f0f0; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <span style="font-size: 10px;">No Image</span>
                                                </div>
                                            @endif
                                            {{ $trx->product->name }}
                                        </div>
                                    </td>
                                    <td>{{ $trx->user->name }}</td>
                                    <td>
                                        @if ($trx->status == 'pending')
                                            <span class="badge bg-warning text-white">Pending</span>
                                        @elseif($trx->status == 'paid')
                                            <span class="badge bg-success text-white">Paid</span>
                                        @elseif($trx->status == 'cancelled')
                                            <span class="badge bg-danger text-white">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($trx->status) }}</span>
                                        @endif
                                    </td>
                                    <td>Rp{{ number_format($trx->amount, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('seller.penjualan.show', $trx->id) }}"
                                            class="btn btn-outline-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada transaksi penjualan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
