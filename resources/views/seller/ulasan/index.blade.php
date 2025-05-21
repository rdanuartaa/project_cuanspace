@extends('layouts.seller')
@section('title', 'Ulasan Produk')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Ulasan Produk</h4>
            <!-- Rating Rata-Rata -->
            <div class="mb-2 text-center">
                <strong class="font-semibold">Rating Rata-Rata Toko:</strong>
                <span class="text-xl font-bold">{{ number_format($averageRating, 2) }}</span>/5 ⭐
            </div>
            <!-- Pesan Kualitas Toko -->
            <div class="text-center">
                @if ($averageRating >= 4.5)
                    <p class="text-green-700 font-medium">
                        Toko Anda sudah bagus! Pertahankan pelayanan dan kualitas produk untuk menjaga kepercayaan pembeli.
                    </p>
                @elseif ($averageRating > 0)
                    <p class="text-yellow-700 font-medium">
                        Masih ada ruang untuk perbaikan. Perhatikan kualitas produk dan responsivitas dalam melayani
                        pembeli.
                    </p>
                @else
                    <p class="text-gray-600 italic">
                        Belum ada ulasan untuk toko Anda. Mulailah dengan memberikan pelayanan terbaik agar pembeli
                        meninggalkan ulasan positif.
                    </p>
                @endif
            </div>
            <form method="GET" action="{{ route('seller.ulasan.index') }}" class="row my-2 g-2 align-items-center">
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
            </form>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Pembeli</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $loopIndex => $review)
                            <tr>
                                <td>{{ $loopIndex + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($review->product)
                                            <img src="{{$review->product->thumbnail_url}}"
                                                alt="{{ $review->product->name}}"
                                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                        @else
                                            <div
                                                style="width: 40px; height: 40px; background-color: #f0f0f0; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 10px;">No Image</span>
                                            </div>
                                        @endif
                                        {{ $review->product->name ?? 'Produk tidak tersedia' }}
                                    </div>
                                </td>
                                <td>{{ $review->user->name ?? 'User tidak tersedia' }}</td>
                                <td>{{ $review->rating }} / 5</td>
                                <td>{{ $review->comment ?? '-' }}</td>
                                <td>{{ $review->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada ulasan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endsection
