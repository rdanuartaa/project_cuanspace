{{-- resources/views/seller/produk/edit.blade.php --}}
@extends('layouts.seller')
@section('title', 'Edit Produk')
@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center">Edit Produk</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.produk.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama produk" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi produk" required>{{ old('description', $product->description) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" step="0.01" placeholder="0.00" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $product->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Thumbnail</label>
                    
                    @if($product->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}" 
                                style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 4px;">
                        </div>
                    @endif
                    
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah thumbnail</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">File Digital</label>
                    
                    @if($product->digital_file)
                        <div class="mb-2">
                            <span class="text-success">
                                <i class="mdi mdi-check-circle"></i> File sudah terupload
                            </span>
                            <span class="ms-2 text-muted">{{ basename($product->digital_file) }}</span>
                        </div>
                    @endif
                    
                    <input type="file" name="digital_file" class="form-control">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file digital</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $product->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                
                <div class="text-end">
                    <a href="{{ route('seller.produk') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection