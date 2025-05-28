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
        <h3 class="title text-center">Riwayat Pembelian Produk</h3>
    </div>
</div>

<!-- Product Detail Section -->
<div class="container">
    @forelse ($transactions as $trx)
        <div class="single-product-detail product-bundle product-aff mt-4">
            <div class="row">
                <!-- Kolom Kiri: Gambar Produk -->
                <div class="col-xs-12 col-sm-5 col-md-6">
                    <div class="product-images">
                        <div class="main-img js-product-slider">
                            @if ($trx->product->thumbnail)
                                <a href="#" class="hover-images effect">
                                    <img src="{{ asset('storage/thumbnails/' . $trx->product->thumbnail) }}"
                                        alt="{{ $trx->product->name }}" class="img-responsive"
                                        style="width: 650px; height: 650px; object-fit: cover; border-radius: 4px;">
                                </a>
                            @else
                                <a href="#" class="hover-images effect">
                                    <img src="https://via.placeholder.com/600x800?text=Tidak+Ada+Foto "
                                        alt="Placeholder" class="img-responsive">
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
                            <a href="#">{{ $trx->product->name }}</a>
                        </h3>

                        <!-- Harga -->
                        <div class="product-price">
                            <span>{{ 'Rp ' . number_format($trx->amount, 0, ',', '.') }}</span>
                        </div>

                        <!-- Tombol Download -->
                        <div class="action v3 mt-4">
                            @if ($trx->download_count < 3)
                                <a href="{{ route('main.download.agree', $trx->product_id) }}" class="link-ver1 add-cart">
                                    Download ({{ $trx->download_count }}/3)
                                </a>
                            @else
                                <p class="text-danger">Maksimal download tercapai</p>
                            @endif
                        </div>

                        <!-- Tampilkan Form Ulasan atau Pesan Sudah Ulas -->
                        @php
                            // Cek apakah user sudah pernah mengulas produk ini
                            $hasReviewed = $trx->product->reviews()
                                                ->where('user_id', auth()->id())
                                                ->exists();
                        @endphp

                        @if(!$hasReviewed)
                            <!-- Form Ulasan -->
                            <div class="mt-4">
                                <form action="{{ route('main.review.store', $trx->product_id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Rating:</label>
                                        <select name="rating" id="rating" class="form-select" required>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Ulasan:</label>
                                        <textarea name="comment" id="comment" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="link-ver1 add-cart mt-4">Kirim Ulasan</button>
                                </form>
                            </div>
                        @else
                            <div class="mt-4 alert alert-success">
                                Anda sudah memberikan ulasan untuk produk ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="container text-center">
            <p>Belum ada pembelian.</p>
        </div>
    @endforelse
</div>

@endsection
