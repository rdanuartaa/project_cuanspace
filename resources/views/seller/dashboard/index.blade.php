@extends('layouts.seller')

@section('title', 'Dashboard Seller')

@section('content')
<div class="container">

    {{-- Total Saldo dan Penghasilan (tanpa card) --}}
    <div class="d-flex justify-content-end mb-3 gap-4">
        <div>
            <p class="mb-1 fw-semibold">Total Saldo Saat Ini</p>
            <h4 class="text-success">Rp {{ number_format($totalSaldo ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div>
            <p class="mb-1 fw-semibold">Total Penghasilan</p>
            <h4 class="text-primary">Rp {{ number_format($totalPenghasilan ?? 0, 0, ',', '.') }}</h4>
        </div>
    </div>

    {{-- Baris sejajar Status Akun Seller dan Produk Baru --}}
    <div class="row g-4 mb-4">

        {{-- Status Akun Seller --}}
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Status Akun Seller</h4>
                    @if ($seller)
                        <span class="badge
                            {{ $seller->status == 'pending' ? 'bg-warning' :
                               ($seller->status == 'active' ? 'bg-success' : 'bg-danger') }}">
                            {{ ucfirst($seller->status) }}
                        </span>
                        <p class="mt-2">
                            @if ($seller->status == 'pending')
                                Menunggu verifikasi dari admin. Mohon tunggu konfirmasi.
                            @elseif ($seller->status == 'active')
                                Akun Anda telah diverifikasi. Anda dapat mulai menjual produk!
                            @else
                                Pendaftaran Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.
                            @endif
                        </p>
                    @else
                        <p>Belum terdaftar sebagai seller. <a href="{{ route('seller.register') }}">Daftar sekarang</a>.</p>
                    @endif
                </div>
            </div>
        </div>
        {{-- Produk Baru --}}
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-0">Produk Baru Dipublish</h5>
                </div>
                <div class="card-body p-2">
                    @if($produkBaru->isEmpty())
                        <p class="text-center my-3">Tidak ada produk baru yang dipublish.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($produkBaru as $produk)
                                <li class="list-group-item d-flex align-items-center">
                                    <img
                                        src="{{ $produk->thumbnail ? Storage::url('thumbnails/' . $produk->thumbnail)  : asset('images/default-product.png') }}"
                                        alt="Produk"
                                        class="rounded me-3"
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $produk->name }}</h6>
                                        <small class="text-muted">
                                            Diterbitkan: {{ optional($produk->created_at)->format('d M Y') ?? '-' }}
                                        </small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>

    </div> {{-- end row --}}

    {{-- Statistik lain bisa kamu letakkan di sini --}}
    <div class="row g-3">
        @php
            $stats = [
                ['title' => 'Total Produk Diterbitkan', 'value' => $totalProduk ?? 0],
                ['title' => 'Total Transaksi Berhasil', 'value' => $totalTransaksiBerhasil ?? 0],
                ['title' => 'Rating Toko', 'value' => number_format($ratingToko ?? 0, 2)],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <p class="mb-1 fw-semibold">{{ $stat['title'] }}</p>
                    <h3 class="mb-0">{{ $stat['value'] }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
