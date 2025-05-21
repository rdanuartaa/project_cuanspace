{{-- resources/views/seller/produk/dashboard.blade.php --}}
@extends('layouts.seller')
@section('title', 'Dashboard Produk')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active ps-0" id="overview-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="statistics-tab" data-bs-toggle="tab" href="#statistics" role="tab" aria-selected="false">Statistik</a>
                    </li>
                </ul>
                <div>
                    <div class="btn-wrapper">
                        <a href="{{ route('seller.produk.create') }}" class="btn btn-primary text-white me-0">
                            <i class="icon-plus"></i> Tambah Produk
                        </a>
                    </div>
                </div>
            </div>

            <div class="tab-content tab-content-basic">
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <!-- Status Cards -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="statistics-details d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="statistics-title">Total Produk</p>
                                    <h3 class="rate-percentage">{{ $productCount }}</h3>
                                </div>
                                <div>
                                    <p class="statistics-title">Produk Dipublikasikan</p>
                                    <h3 class="rate-percentage">{{ $publishedCount }}</h3>
                                </div>
                                <div>
                                    <p class="statistics-title">Produk Draft</p>
                                    <h3 class="rate-percentage">{{ $draftCount }}</h3>
                                </div>
                                <div>
                                    <p class="statistics-title">Produk Diarsipkan</p>
                                    <h3 class="rate-percentage">{{ $archivedCount }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Products -->
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Produk Terbaru</h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Thumbnail</th>
                                                    <th>Nama Produk</th>
                                                    <th>Harga</th>
                                                    <th>Status</th>
                                                    <th>Dibuat</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentProducts as $product)
                                                    <tr>
                                                        <td>
                                                            @if($product->thumbnail && file_exists(public_path('storage/' . $product->thumbnail)))
                                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" 
                                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                            @else
                                                                <div style="width: 50px; height: 50px; background-color: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                                    <span style="font-size: 8px;">No Image</span>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->name }}</td>
                                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                                        <td>
                                                            @if($product->status == 'draft')
                                                                <span class="badge bg-warning text-dark">Draft</span>
                                                            @elseif($product->status == 'published')
                                                                <span class="badge bg-success">Published</span>
                                                            @else
                                                                <span class="badge bg-secondary">Archived</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $product->created_at->format('d M Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('seller.produk.edit', $product->id) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">Belum ada produk.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 text-end">
                                        <a href="{{ route('seller.produk') }}" class="btn btn-outline-primary btn-sm">Lihat Semua Produk</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="statistics" role="tabpanel" aria-labelledby="statistics">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Statistik Produk</h4>
                                    <p class="text-muted">Statistik akan ditampilkan di sini.</p>
                                    <!-- Placeholder untuk grafik statistik -->
                                    <div style="height: 300px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                        <p class="text-muted">Grafik statistik produk</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection