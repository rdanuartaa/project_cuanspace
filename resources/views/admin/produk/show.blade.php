@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="m-0">Detail Produk</h4>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-outline-secondary btn-sm">
                           </i> Kembali
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-4">
                            @if($product->thumbnail)
                                <img src="{{ asset('storage/thumbnails/' . $product->thumbnail) }}" 
                                     alt="{{ $product->name }}" 
                                     class="img-fluid rounded" 
                                     style="max-height: 300px; object-fit: cover; width: 100%;">
                            @else
                                <div class="bg-light rounded" style="height: 300px;"></div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Nama Produk</div>
                                <div class="col-8">{{ $product->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Deskripsi</div>
                                <div class="col-8">{{ $product->description }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Harga</div>
                                <div class="col-8">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Kategori</div>
                                <div class="col-8">{{ $product->kategori->nama_kategori ?? 'Tidak berkategori' }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Status</div>
                                <div class="col-8">
                                    <span class="badge bg-success">Publikasi</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">Penjual</div>
                                <div class="col-8">{{ $product->seller->brand_name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-4 text-muted">File Digital</div>
                                <div class="col-8">
                                    @if($product->digital_file)
                                        <a href="{{ asset('storage/digital_files/' . $product->digital_file) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary px-3">
                                            Unduh File
                                        </a>
                                    @else
                                        <span class="text-danger">Tidak ada file digital</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-5 text-muted">Dibuat pada</div>
                                <div class="col-7">{{ $product->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <div class="row">
                                <div class="col-5 text-muted">Terakhir diperbarui</div>
                                <div class="col-7">{{ $product->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-danger px-4 text-white" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Hapus Produk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.produk.destroy', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penghapusan</label>
                        <textarea name="alasan_penghapusan" class="form-control" rows="3" placeholder="Jelaskan alasan penghapusan produk" required></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <strong>Peringatan:</strong> Produk akan dihapus sepenuhnya dan tidak dapat dikembalikan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .row.mb-3 {
        margin-bottom: 1rem;
        align-items: center;
    }
    .text-muted {
        color: #6c757d !important;
    }
    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }

    .btn-danger {
        color: white !important;
    }
</style>
@endpush