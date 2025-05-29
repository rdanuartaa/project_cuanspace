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
            <h3 class="title text-center">Checkout</h3>
        </div>
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
                        <!-- Nama Produk -->
                        <h3 class="product-title">
                            <a href="#">{{ $product->name }}</a>
                        </h3>

                        <!-- Harga -->
                        <div class="product-price">
                            <span>{{ $product->formatted_price }}</span>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <p class="product-desc">{{ $product->description }}</p>

                        <!-- Form Checkout -->
                        <div class="action v3">
                            @php
                                $transaction = \App\Models\Transaction::where([
                                    'user_id' => auth()->id(),
                                    'product_id' => $product->id,
                                    'status' => 'pending',
                                ])
                                    ->latest()
                                    ->first();
                            @endphp

                            @if (!$transaction)
                                <form id="checkoutForm" action="{{ route('main.processCheckout', $product->id) }}"
                                    method="POST">
                                    @csrf
                                    <h5 class="mt-4 mb-4">Ringkasan Pembelian</h5>
                                    <table class="table">
                                        <tr>
                                            <td>Nama Pembeli</td>
                                            <td>{{ auth()->user()->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email Pembeli</td>
                                            <td><input type="email" name="email"
                                                    value="{{ old('email', auth()->user()->email) }}" required></td>
                                        </tr>
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

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" name="agree" id="agreeCheck"
                                            required>
                                        <h5 class="form-check-label" for="agreeCheck">
                                            Saya menyetujui bahwa produk digital tidak dapat direfund setelah pembelian.
                                        </h5>
                                    </div>

                                    <button type="submit" class="link-ver1 add-cart">Bayar Sekarang</button>
                                </form>
                            @elseif($transaction->status == 'pending')
                                <p>Status: <strong>Menunggu Pembayaran</strong></p>
                                <form action="{{ route('main.confirmPayment', $transaction->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="link-ver1 add-cart">Saya Sudah Bayar</button>
                                </form>
                            @else
                                <div class="alert alert-info">Kamu sudah membeli produk ini.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Tambahan -->
    <div class="information">
        <ul>
            <!-- Produk Terjual -->
            <li class="info-center text-center">
                <span>Produk Terjual :</span>
                <a>{{ $jumlahTerjual ?? 0 }}</a> Kali
            </li>
            <!-- Kategori Produk -->
            <li class="info-center bd-rl text-center">
                <span>Kategori Produk :</span>
                <a>
                    {{ optional($product->kategori)->nama_kategori ?? 'Uncategorized' }}
                </a>
            </li>
            <!-- Penjual -->
            <li class="info-center text-center">
                <span>Penjual :</span>
                <a href="#">
                    {{ optional($product->seller)->brand_name ?? 'Unknown Seller' }}
                </a>
            </li>
        </ul>
    </div>
@endsection

<!-- Load Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js " data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("checkoutForm");

        form.addEventListener("submit", function(e) {
            e.preventDefault(); // Cegah submit default

            const formData = new FormData(form);
            fetch("{{ route('main.processCheckout', $product->id) }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        snap.pay(data.snap_token); // Tampilkan popup Midtrans
                    } else {
                        alert("Gagal memulai pembayaran: " + (data.error || "Unknown error"));
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat memproses pembayaran.");
                });
        });
    });
</script>
