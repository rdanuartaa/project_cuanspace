@extends('layouts.admin')

@section('title', 'Kelola Produk')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card ">
            <div class="card-body">
                <h4 class="m-0">Kelola Produk</h4>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <form method="GET" action="{{ route('admin.produk.index') }}" class="d-flex">
                                <select name="kategori" class="form-select form-select-sm me-2" style="width: 200px;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                <select name="status" class="form-select form-select-sm" style="width: 200px;"
                                    onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                                    </option>
                                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

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
                                        <td>{{ $products->firstItem() + $index }}</td>
                                        <td>
                                            @if ($product->thumbnail)
                                                <img src="{{ asset('storage/thumbnails/' . $product->thumbnail) }}"
                                                    alt="{{ $product->name }}" class="rounded"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded" style="width: 50px; height: 50px;"></div>
                                            @endif
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->kategori->nama_kategori ?? 'Tidak berkategori' }}</td>
                                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $product->status == 'published' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.produk.show', $product->id) }}"
                                                    class="btn btn-outline-info btn-sm">
                                                    Detail
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger btn-delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $product->id }}">
                                                    Hapus
                                                </button>

                                                <!-- Modal Konfirmasi Hapus -->
                                                <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Konfirmasi Hapus Produk</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form
                                                                action="{{ route('admin.produk.destroy', $product->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Alasan Penghapusan</label>
                                                                        <textarea name="alasan_penghapusan" class="form-control" rows="3" placeholder="Jelaskan alasan penghapusan produk"
                                                                            required></textarea>
                                                                    </div>
                                                                    <div class="alert alert-warning">
                                                                        <strong>Peringatan:</strong> Produk akan dihapus
                                                                        sepenuhnya dan tidak dapat dikembalikan.
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-danger text-white">Hapus
                                                                        Produk</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
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
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .table> :not(caption)>*>* {
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
