@extends('layouts.seller')
@section('title', 'Detail Penjualan')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail Penjualan</h4>

            @if(session('success'))
                <div class="alert alert-success rounded fade show" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded fade show" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>Kode Transaksi</th>
                            <td>{{ $transaction->transaction_code }}</td>
                        </tr>
                        <tr>
                            <th>Produk</th>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($transaction->product->thumbnail && file_exists(public_path('storage/' . $transaction->product->thumbnail)))
                                        <img src="{{ asset('storage/' . $transaction->product->thumbnail) }}" alt="{{ $transaction->product->name }}"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                    @else
                                        <div style="width: 40px; height: 40px; background-color: #f0f0f0; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                                            <span style="font-size: 10px;">No Image</span>
                                        </div>
                                    @endif
                                    {{ $transaction->product->name }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Pembeli</th>
                            <td>{{ $transaction->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($transaction->status == 'pending')
                                    <span class="badge bg-warning text-white">Pending</span>
                                @elseif($transaction->status == 'paid')
                                    <span class="badge bg-success text-white">Paid</span>
                                @elseif($transaction->status == 'cancelled')
                                    <span class="badge bg-danger text-white">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('seller.penjualan.index') }}" class="btn btn-outline-info btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection
