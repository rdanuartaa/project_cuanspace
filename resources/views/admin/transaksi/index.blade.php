@extends('layouts.admin')
@section('title', 'Kelola Transaksi')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Transaksi</h4>

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

                <div class="table-responsive">
                    <table class="table">
                        <thead class="table">
                            <tr>
                                <th>No</th>
                                <th>Kode Transaksi</th>
                                <th>Produk</th>
                                <th>Buyer</th>
                                <th>Seller</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksi as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 + ($transaksi->currentPage() - 1) * $transaksi->perPage() }}</td>
                                    <td>{{ $t->transaction_code }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($t->product && $t->product->thumbnail_url)
                                                <img src="{{ $t->product->thumbnail_url }}"
                                                    alt="{{ $t->product->name }}"
                                                    style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                            @else
                                                <div
                                                    style="width: 40px; height: 40px; background-color: #f0f0f0; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <span style="font-size: 10px;">No Image</span>
                                                </div>
                                            @endif
                                            {{ $t->product->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td>{{ $t->user->name ?? '-' }}</td>
                                    <td>{{ $t->product->seller->brand_name ?? '-' }}</td>
                                    <td>
                                        @if ($t->status == 'pending')
                                            <span class="badge bg-warning text-white">Pending</span>
                                        @elseif($t->status == 'paid')
                                            <span class="badge bg-success text-white">Paid</span>
                                        @elseif($t->status == 'cancelled')
                                            <span class="badge bg-danger text-white">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($t->status) }}</span>
                                        @endif
                                    </td>
                                    <td>Rp{{ number_format($t->amount, 0, ',', '.') }}</td>
                                    <td>{{ $t->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.transaksi.show', $t->id) }}"
                                            class="btn btn-outline-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transaksi->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transaksi->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
