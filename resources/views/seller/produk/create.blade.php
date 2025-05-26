{{-- resources/views/seller/produk/create.blade.php --}}
@extends('layouts.seller')
@section('title', 'Tambah Produk')
@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center">TAMBAH PRODUK BARU</h2>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.produk.store') }}" method="POST" enctype="multipart/form-data" id="produkForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi produk" required>{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}" step="0.01" placeholder="0.00" required>
                    </div>
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*" required>
                    <small class="text-muted">Gambar untuk thumbnail produk (format: JPG, PNG, GIF)</small>
                    @error('thumbnail')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    {{-- Thumbnail Preview --}}
                    <div id="thumbnailPreview" class="mt-2" style="display:none;">
                        <img src="" alt="Thumbnail Preview"
                             style="max-width: 200px; max-height: 200px; object-fit: cover; border-radius: 4px;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">File Digital</label>
                    <input type="file" name="digital_file" class="form-control" required>
                    <small class="text-muted">File digital yang akan dijual (format: ZIP, RAR, PDF, dll)</small>
                    @error('digital_file')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-end">
                    <a href="{{ route('seller.produk.index') }}" class="btn btn-outline-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-outline-primary btn-sm" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Mencegah form dikirim berkali-kali
    document.getElementById('produkForm').addEventListener('submit', function(e) {
        // Nonaktifkan tombol submit setelah diklik
        document.getElementById('btnSubmit').disabled = true;
        document.getElementById('btnSubmit').innerHTML = 'Menyimpan...';
    });

    // Thumbnail preview
    document.querySelector('input[name="thumbnail"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('thumbnailPreview');
        const previewImg = preview.querySelector('img');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endpush
@endsection
