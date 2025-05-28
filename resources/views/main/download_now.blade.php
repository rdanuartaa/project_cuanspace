@extends('layouts.main')
@section('content')
    <style>
        .product-detail-section {
            margin-bottom: 2rem;
        }

        .no-products-message>*+* {
            margin-top: 1.5rem;
        }
    </style>

    <div class="product-related">
        <div class="container container-42">
            <h3 class="title text-center">Produk Siap Diunduh</h3>
        </div>
    </div>

    <!-- Product Detail Section -->
    <div class="container">
        <div class="single-product-detail product-bundle product-aff">
            <div class="row">
                <!-- Kolom Kiri: Gambar Produk -->
                <div class="col-xs-12 col-sm-5 col-md-6">
                    <div class="product-images">
                        <div class="main-img js-product-slider">
                            @if ($product->thumbnail)
                                <a href="#" class="hover-images effect">
                                    <img src="{{ asset('storage/thumbnails/' . $product->thumbnail) }}"
                                        alt="{{ $product->name }}" class="img-responsive"
                                        style="width: 650px; height: 650px; object-fit: cover; border-radius: 4px;">
                                </a>
                            @else
                                <a href="#" class="hover-images effect">
                                    <img src="https://via.placeholder.com/600x800?text=Tidak+Ada+Foto " alt="Placeholder"
                                        class="img-responsive">
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Informasi Produk -->
                <div class="col-xs-12 col-sm-7 col-md-6">
                    <div class="single-product-info">
                        <!-- Nama Produk -->
                        <h3 class="product-title">
                            <a href="#">{{ $product->name }}</a>
                        </h3>

                        <!-- Harga -->
                        <div class="product-price">
                            <span>{{ 'Rp ' . number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>

                        <!-- Tombol Download -->
                        <div class="action v3 mt-4">
                            @if ($downloadRemaining > 0)
                                <a href="{{ route('main.download.agree', $product->id) }}" class="link-ver1 add-cart">
                                    Download Sekarang ({{ $downloadRemaining }}x tersisa)
                                </a>
                            @else
                                <p class="text-danger">Anda telah mencapai batas download.</p>
                            @endif
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('main.order.history') }}" class="btn btn-outline-secondary">
                                ← Kembali ke Riwayat Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
