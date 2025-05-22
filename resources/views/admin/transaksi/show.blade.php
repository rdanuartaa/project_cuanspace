@extends('layouts.admin')
@section('title', 'Detail Transaksi')

@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Detail Transaksi</h4>

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
                            <td>{{ $detail->transaction_code }}</td>
                        </tr>
                        <tr>
                            <th>Produk</th>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($detail->product)
                                        <img src="{{ $detail->product->thumbnail_url ?? '#' }}"
                                            alt="{{ $detail->product->name }}"
                                            style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                    @else
                                        <div style="width: 40px; height: 40px; background-color: #f0f0f0; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                                            <span style="font-size: 10px;">No Image</span>
                                        </div>
                                    @endif
                                    {{ $detail->product->name ?? '-' }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Pembeli</th>
                            <td>{{ $detail->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Seller</th>
                            <td>{{ $detail->product->seller->brand_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($detail->status == 'pending')
                                    <span class="badge bg-warning text-white">Pending</span>
                                @elseif($detail->status == 'paid')
                                    <span class="badge bg-success text-white">Paid</span>
                                @elseif($detail->status == 'cancelled')
                                    <span class="badge bg-danger text-white">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($detail->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>Rp{{ number_format($detail->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Waktu Transaksi</th>
                            <td>{{ $detail->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('admin.transaksi.index') }}" class="btn btn-outline-info btn-sm">Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection
