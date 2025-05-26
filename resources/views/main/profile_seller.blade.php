@extends('layouts.main')
@section('title', 'Profil Penjual' . $seller->brand_name)
@section('content')

<h2>Profil Penjual</h2>

<div class="card">
    <div class="card-body">
        <h5>Nama Toko: {{ $seller->brand_name }}</h5>
        <p>Email: {{ $seller->user->email }}</p>
        <p>Lokasi: {{ $seller->location ?? 'Tidak tersedia' }}</p>
        <p>Dibuat: {{ $seller->created_at->format('d M Y') }}</p>
    </div>
</div>

<h3 class="mt-4">Produk dari Penjual Ini</h3>
@if ($seller->products->isNotEmpty())
    <div class="row">
        @foreach ($seller->products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('storage/thumbnails/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                         class="card-img-top" style="height: 180px; object-fit: cover;">
                    <div class="card-body">
                        <h6>{{ $product->name }}</h6>
                        <p>Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                        <a href="{{ route('public.produk.show', $product->id) }}" class="btn btn-primary btn-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-muted">Belum ada produk yang diunggah oleh penjual ini.</p>
@endif

@endsection
