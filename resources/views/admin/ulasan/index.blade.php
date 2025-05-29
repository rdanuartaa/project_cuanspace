@extends('layouts.admin')
@section('title', 'Semua Ulasan Produk')
@section('content')
    <!-- Notifikasi Sukses atau Error -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Tabel Ulasan -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Semua Ulasan Produk</h3>
            </div>

            <!-- Filter -->
            <form method="GET" action="{{ route('admin.ulasan.index') }}" class="row my-2 g-2 align-items-center">
                <div class="col-md-3">
                    <select name="rating" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Rating</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Bintang</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Bintang</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Bintang</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Bintang</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Bintang</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="seller_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Penjual --</option>
                        @foreach ($sellers as $seller)
                            <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                {{ $seller->brand_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="product_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Penjual</th>
                            <th>Pembeli</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $index => $review)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($review->product && $review->product->thumbnail)
                                            <img src="{{ asset('storage/thumbnails/' . $review->product->thumbnail) }}"
                                                alt="{{ $review->product->name }}"
                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                        @else
                                            <div
                                                style="width: 40px; height: 40px; background-color: #f0f0f0; border-radius: 4px;">
                                            </div>
                                        @endif
                                        {{ optional($review->product)->name ?? 'Produk tidak tersedia' }}
                                    </div>
                                </td>
                                <td>{{ optional($review->product->seller)->brand_name ?? 'Penjual Dihapus' }}</td>
                                <td>{{ optional($review->user)->name ?? 'Pengguna Dihapus' }}</td>
                                <td>{{ $review->rating }} / 5</td>
                                <td>{{ $review->comment ?? '-' }}</td>
                                <td>{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.ulasan.destroy', $review->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Belum ada ulasan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $reviews->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .font-semibold {
            font-weight: 60;
        }

        .text-xl {
            font-size: 1.25rem;
        }

        .text-green-700 {
            color: #16a34a;
        }

        .text-yellow-700 {
            color: #ca8a0c;
        }

        .text-gray-600 {
            color: #71717a;
        }

        .italic {
            font-style: italic;
        }

        .table thead th {
            vertical-align: middle;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll("select").forEach(select => {
            select.addEventListener("change", function() {
                this.form.submit();
            });
        });
    </script>
@endpush
