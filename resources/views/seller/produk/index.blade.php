{{-- resources/views/seller/produk/index.blade.php --}}
@extends('layouts.seller')
@section('title', 'Produk Saya')
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title">Kelola Produk</h4>
                <a href="{{ route('seller.produk.create') }}" class="btn btn-outline-success btn-sm">Tambah Produk</a>
            </div>

            <!-- Filter dropdown -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select id="kategori-filter" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
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

            @if(session('success'))
                <div class="alert alert-success rounded fade show" role="alert" style="transition: opacity 1s;">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        let alert = document.querySelector('.alert');
                        alert.style.opacity = 0;
                        setTimeout(function() {
                            alert.remove();
                        }, 1000);  // Menghapus notifikasi setelah animasi selesai
                    }, 3000);  // Hilang setelah 3 detik
                </script>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded fade show" role="alert" style="transition: opacity 1s;">
                    {{ session('error') }}
                </div>
                <script>
                    setTimeout(function() {
                        let alert = document.querySelector('.alert');
                        alert.style.opacity = 0;
                        setTimeout(function() {
                            alert.remove();
                        }, 1000);
                    }, 5000);
                </script>
            @endif

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
                        @forelse($products ?? [] as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($product->thumbnail)
                                        <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" 
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <span style="font-size: 10px;">No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->kategori->nama_kategori ?? 'Tidak ada kategori' }}</td>
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
                                <td>
                                    <a href="{{ route('seller.produk.edit', $product->id) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                        Hapus
                                    </button>
                                    
                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" 
                                        aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">
                                                        Konfirmasi Hapus
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus produk <strong>{{ $product->name }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('seller.produk.destroy', $product->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            @if(isset($products) && $products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter functionality
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
            
            // Update or remove kategori parameter
            if (kategoriId) {
                url.searchParams.set('kategori', kategoriId);
            } else {
                url.searchParams.delete('kategori');
            }
            
            // Update or remove status parameter
            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }
            
            window.location.href = url.toString();
        }
    });
</script>
@endpush