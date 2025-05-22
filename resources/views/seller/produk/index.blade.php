@extends('layouts.seller')
@section('title', 'Produk Saya')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Notifikasi produk dihapus admin -->
            @if(session('seller_product_deleted'))
                <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                    Produk "{{ session('seller_product_deleted')['product_name'] }}" berhasil dihapus dari sistem.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <!-- Sisa code view tetap sama seperti sebelumnya -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4>Kelola Produk</h4>
                        <a href="{{ route('seller.produk.create') }}" class="btn btn-outline-success btn-sm">
                            Tambah Produk
                        </a>
                    </div>

                    <!-- Filter dan Tabel Produk -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <select id="kategori-filter" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" 
                                        {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="status-filter" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabel Produk -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Thumbnail</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $product)
                                    <tr class="{{ $product->deletion ? 'table-danger' : '' }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($product->thumbnail)
                                                <img src="{{ asset('storage/thumbnails/' . $product->thumbnail) }}" 
                                                     alt="{{ $product->name }}" 
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 4px;"></div>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->name }}
                                            @if($product->deletion)
                                                <div class="alert alert-danger d-flex justify-content-center align-items-center p-2 mt-2" role="alert">
                                                    <button type="button" class="btn btn-sm btn-outline-light" data-bs-toggle="modal" data-bs-target="#deletionReasonModal{{ $product->id }}">
                                                        Lihat Alasan Produk Dihapus Admin
                                                    </button>
                                                </div>

                                                <!-- Modal Alasan Penghapusan -->
                                                <div class="modal fade" id="deletionReasonModal{{ $product->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Alasan Penghapusan Produk</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <div class="col-4 text-muted">Nama Produk</div>
                                                                    <div class="col-8">{{ $product->name }}</div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-4 text-muted">Dihapus pada</div>
                                                                    <div class="col-8">{{ $product->deletion->created_at->format('d M Y H:i') }}</div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-4 text-muted">Alasan Penghapusan</div>
                                                                    <div class="col-8">
                                                                        <blockquote class="blockquote bg-light p-2 rounded">
                                                                            {{ $product->deletion->deletion_reason }}
                                                                        </blockquote>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $product->kategori->nama_kategori ?? 'Tidak ada kategori' }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($product->deletion)
                                                <span class="badge bg-danger">Dihapus Admin</span>
                                            @else
                                                @if($product->status == 'draft')
                                                    <span class="badge bg-warning text-dark">Draft</span>
                                                @elseif($product->status == 'published')
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-secondary">Archived</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$product->deletion)
                                                <a href="{{ route('seller.produk.edit', $product->id) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                                    Hapus
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada produk. Silahkan tambahkan produk baru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-danger {
        background-color: #f8d7da !important;
    }
    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border-color: #bee5eb;
    }
    .alert-danger {
        background-color: #dc3545;
        color: white;
        border: none;
        margin: 0;
    }
    .alert-danger .btn-outline-light {
        color: white;
        border-color: white;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .alert-danger .btn-outline-light:hover {
        background-color: rgba(255,255,255,0.2);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriFilter = document.getElementById('kategori-filter');
        const statusFilter = document.getElementById('status-filter');
        
        if (kategoriFilter && statusFilter) {
            kategoriFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
        }
        
        function applyFilters() {
            const kategoriId = kategoriFilter.value;
            const status = statusFilter.value;
            
            let url = new URL(window.location.href);
            
            if (kategoriId) {
                url.searchParams.set('kategori', kategoriId);
            } else {
                url.searchParams.delete('kategori');
            }
            
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            
            window.location.href = url.toString();
        }

        // Hapus notifikasi otomatis setelah 5 detik
        const alert = document.querySelector('.alert-info');
        if (alert) {
            setTimeout(() => {
                alert.classList.remove('show');
                alert.classList.add('d-none');
            }, 5000);
        }
    });
</script>
@endpush