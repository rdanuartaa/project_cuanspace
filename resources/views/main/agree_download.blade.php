@extends('layouts.main')
@section('content')

<style>
    .product-detail-section {
        margin-bottom: 2rem;
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

<div class="product-related">
    <div class="container container-42">
        <h3 class="title text-center">Syarat & Ketentuan Download</h3>
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
                        <a href="#">{{ $product->name }}</a>
                    </h3>
                    <!-- Deskripsi Syarat & Ketentuan -->
                    <p>Anda harus membaca dan menyetujui syarat berikut sebelum dapat mendownload produk ini:</p>
                    <ul>
                        <li>Produk hanya boleh digunakan untuk tujuan pribadi/non-komersial.</li>
                        <li>Tidak diperbolehkan menyebarluaskan file tanpa izin dari penjual.</li>
                        <li>Penjual tidak bertanggung jawab atas kerusakan atau kesalahan dalam produk.</li>
                        <li>Batas maksimum download adalah 3x per pembelian.</li>
                    </ul>

                    <!-- Form Persetujuan -->
                    <form action="{{ route('main.download.accept', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="link-ver1 add-cart">Saya Setuju</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
