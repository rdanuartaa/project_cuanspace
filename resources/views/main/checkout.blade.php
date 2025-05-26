@extends('layouts.main')

@section('content')
<div class="container">
    <h3>Checkout Produk</h3>

    <div class="card mb-3">
        <div class="card-body">
            <h5>{{ $product->name }}</h5>
            <p>{{ $product->formatted_price }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Cek transaksi pembeli --}}
    @php
        $transaction = \App\Models\Transaction::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->latest()
            ->first();
    @endphp

    @if(!$transaction)
        <form action="{{ route('main.processCheckout', $product->id) }}" method="POST">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <label>Email untuk menerima produk</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
            </div>

            {{-- Catatan tambahan --}}
            <div class="mb-3">
                <label>Catatan tambahan (opsional)</label>
                <textarea name="note" class="form-control" rows="3" placeholder="Misalnya: ubah warna ke biru, dsb..."></textarea>
            </div>

            {{-- Ringkasan produk --}}
            <h5>Ringkasan Pembelian</h5>
            <table class="table">
                <tr>
                    <td>Produk</td>
                    <td>{{ $product->name }}</td>
                </tr>
                <tr>
                    <td>Harga</td>
                    <td>{{ $product->formatted_price }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td><strong>{{ $product->formatted_price }}</strong></td>
                </tr>
            </table>

            {{-- Syarat & Ketentuan --}}
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="agree" required>
                <label class="form-check-label">
                    Saya menyetujui bahwa produk digital tidak dapat direfund setelah pembelian.
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
        </form>

    @elseif($transaction->status == 'pending')
        <p>Status: <strong>Menunggu Pembayaran</strong></p>
        <form action="{{ route('main.confirmPayment', $transaction->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Saya Sudah Bayar</button>
        </form>
    @else
        <div class="alert alert-info">Kamu sudah membeli produk ini.</div>
    @endif
</div>
@endsection
