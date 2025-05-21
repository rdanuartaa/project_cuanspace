@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="m-0">Kelola Produk</h4>
                    <div class="d-flex">
                        <select class="form-select form-select-sm me-2" style="width: 200px;">
                            <option>Semua Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <select class="form-select form-select-sm" style="width: 200px;">
                            <option>Semua Status</option>
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
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
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($product->thumbnail)
                                                <img src="{{ asset('storage/thumbnails/' . $product->thumbnail) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="rounded" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded" style="width: 50px; height: 50px;"></div>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->kategori->nama_kategori ?? 'Tidak berkategori' }}</td>
                                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $product->status == 'published' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.produk.show', $product->id) }}" 
                                                   class="btn btn-sm btn-outline-primary me-1 btn-view">
                                                    View
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger btn-delete">Hapus</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">Tidak ada produk</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table > :not(caption) > * > * {
        padding: 12px 15px;
    }
    .table tbody tr {
        vertical-align: middle;
    }
    .btn-view:hover {
        color: white !important;
        background-color: #007bff !important;
    }
    .btn-view {
        transition: all 0.3s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tambahkan event listener untuk tombol hapus
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            // Implementasi modal konfirmasi hapus
            // Atau redirect ke halaman konfirmasi
        });
    });
</script>
@endpush