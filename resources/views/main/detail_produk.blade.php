@extends('layouts.main')
@section('title', $product->name)
@section('content')
    <style>
        .product-detail-section {
            margin-bottom: 2rem;
        }

        .tab-pane {
            padding: 1.5rem 0;
        }

        .rounded-circle {
            border-radius: 50%;
        }

        .no-products-message>*+* {
            margin-top: 1.5rem;
        }

        .review-item {
            margin-bottom: 1rem;
        }

        .rating-average {
            margin-bottom: 1rem;
        }
    </style>
    <div class="container container-42 mt-5">
        <!-- Breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="{{ route('home') }}">Home</a></li>
            @if (optional($product->seller)->brand_name)
                <li><a href="#">{{ optional($product->seller)->brand_name }}</a></li>
            @endif
            <li class="active"><a href="#">{{ $product->name }}</a></li>
        </ul>
    </div>
    <!-- Product Detail Section -->
    <div class="container">
        <div class="single-product-detail product-bundle product-aff">
            <div class="row">
                <!-- Kolom Kiri: Gambar Produk -->
                <div class="col-xs-12 col-sm-5 col-md-6">
                    <div class="product-images">
                        <!-- Gambar Utama -->
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
                        <!-- Rating Star -->
                        <!-- Nama Produk -->
                        <h3 class="product-title">
                            <a href="#">{{ $product->name }}</a>
                        </h3>

                        <!-- Harga -->
                        <div class="product-price">
                            <span>Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <p class="product-desc">{{ $product->description }}</p>

                        <!-- Form Aksi -->
                        @if (!$isSeller)
                            <form action="{{ route('main.processCheckout', $product->id) }}" method="POST">
                                @csrf
                                <div class="action v3">
                                    <button type="submit" class="link-ver1 add-cart">Checkout Sekarang</button>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                Anda tidak dapat membeli produk Anda sendiri.
                            </div>
                        @endif
                        <!-- Share Social -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="single-product-tab center">
        <ul class="nav nav-tabs text-center">
            <li><a data-toggle="pill" href="#review">Ulasan Pembeli ({{ $reviews->count() }})</a></li>
        </ul>
        <div class="tab-content">
            <div id="review" class="tab-pane fade">
                <div class="container mt-2 text-center">
                    @if ($reviews->isNotEmpty())
                        <div class="rating-average mb-4">
                            <h3>Rating Rata-Rata:</h3>
                            <span class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $averageRating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </span>
                            <small class="text-muted">({{ $reviews->count() }} ulasan)</small>
                        </div>

                        <!-- Daftar Ulasan -->
                        <div class="list-group mb-1">
                            @foreach ($reviews as $review)
                                <div class="list-group-item review-item">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $review->user->name ?? 'Pengguna' }}</h6>
                                        <div>
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <span class="text-warning">★</span>
                                                @else
                                                    <span class="text-muted">☆</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="mb-1">{{ $review->comment ?? 'Tidak ada komentar.' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Belum ada ulasan untuk produk ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="information">
        <ul>
            <!-- Produk Terjual -->
            <li class="info-center text-center">
                <span>Produk Terjual :</span>
                <a href="#">{{ $jumlahTerjual }}</a> Kali
            </li>

            <!-- Kategori Produk -->
            <li class="info-center bd-rl text-center">
                <span>Kategori Produk :</span>
                <a href="#">
                    {{ optional($product->kategori)->nama_kategori ?? 'Uncategorized' }}
                </a>
            </li>

            <!-- Penjual -->
            <li class="info-center text-center">
                <span>Profile Penjual :</span>
                <a href="#">
                    {{ optional($product->seller)->brand_name ?? 'Unknown Seller' }}
                </a>
            </li>
        </ul>
    </div>
    <div class="product-related">
        <div class="container container-42">
            <h3 class="title text-center mb-4">Produk Terkait</h3>
            <div class="row">
                @forelse ($relatedProducts as $related)
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="{{ route('public.produk.show', $related->id) }}" class="hover-images effect">
                                <img src="{{ asset('storage/thumbnails/' . $related->thumbnail) }}"
                                    alt="{{ $related->name }}" class="img-responsive"
                                    style="width: 300px; height: 400px; object-fit: cover; border-radius: 4px; ">
                            </a>
                            @auth
                                <a href="{{ route('public.produk.show', $product->id) }}" class="btn-quickview">VIEW
                                    DETAIL</a>
                            @else
                                <a href="javascript:void(0)" onclick="showLoginPrompt()" class="btn-quickview">VIEW
                                    DETAIL</a>
                            @endauth
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title">
                                <a
                                    href="{{ route('public.produk.show', $related->id) }}">{{ \Illuminate\Support\Str::limit($related->name, 25) }}</a>
                            </h3>
                            <div class="product-after-switch">
                                <div class="product-price">Rp{{ number_format($related->price, 0, ',', '.') }}</div>
                                <div class="product-after-button">
                                    @auth
                                        <a href="{{ route('main.processCheckout', $related->id) }}" class="addcart">CHEKOUT
                                            NOW</a>
                                    @else
                                        <a href="javascript:void(0)" onclick="showLoginPrompt()" class="addcart">CHEKOUT
                                            NOW</a>
                                    @endauth
                                </div>
                            </div>
                            <div class="rating-average mb-4">
                                <span class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $averageRating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </span>
                                <small class="text-muted">({{ $reviews->count() }} ulasan)</small>
                            </div>
                            <div class="product-price">
                                <small class="text-muted">by
                                    {{ $related->seller->brand_name ?? 'Unknown Seller' }}</small>
                            </div>
                            <div class="product-category">
                                <small
                                    class="badge badge-secondary">{{ $related->kategori->nama_kategori ?? 'Uncategorized' }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 no-products-message">
                        <h3>Tidak ada produk yang tersedia</h3>
                        <p>Belum ada produk yang dipublikasikan untuk kategori ini.</p>
                        <a href="{{ route('home') }}" class="link-ver1 add-cart">Lihat Semua Produk</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
