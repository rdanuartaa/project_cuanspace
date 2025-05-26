@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <h3>Daftar Produk Yang Dibeli</h3>

    @forelse ($transactions as $trx)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $trx->product->name }}</h5>
                <p>Rp {{ number_format($trx->amount) }}</p>
                <a href="{{ route('main.download', $trx->product_id) }}" class="btn btn-primary">Download</a>
            </div>
        </div>
    @empty
        <p>Belum ada pembelian.</p>
    @endforelse
</div>
@endsection
