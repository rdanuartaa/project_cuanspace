@extends('layouts.seller')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            @if($product->thumbnail)
                <img src="{{ asset('storage/thumbnails/'.$product->thumbnail) }}"
                     alt="{{ $product->name }}" class="img-fluid rounded" style="max-height:400px;">
            @else
                <p>Thumbnail tidak tersedia</p>
            @endif
        </div>
        <div class="col-md-6">
            <h2>{{ $product->name }}</h2>
            <p><strong>Harga:</strong> Rp{{ number_format($product->price, 0, ',', '.') }}</p>
            <p><strong>Kategori:</strong> {{ optional($product->kategori)->nama_kategori ?? 'Tidak ada' }}</p>
            <p><strong>Penjual:</strong> {{ optional(optional($product->seller)->user)->name ?? 'Tidak diketahui' }}</p>
            <hr>
            <p>{{ $product->description }}</p>
            <a href="#" class="btn btn-success">Tambah ke Keranjang</a>
        </div>
    </div>
</div>
@endsection
