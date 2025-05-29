@extends('layouts.admin')
@section('content')
    <div class="row g-3 mb-4">
        @php
            $stats = [
                ['title' => 'Total Pengguna', 'value' => $totalPengguna ?? 0],
                ['title' => 'Total Seller', 'value' => $totalSeller ?? 0],
                ['title' => 'Total Produk Diterbitkan', 'value' => $totalProduk ?? 0],
                ['title' => 'Total Transaksi Berhasil', 'value' => $totalTransaksiBerhasil ?? 0],
                ['title' => 'Saldo Pending Platform', 'value' => 'Rp' . number_format($totalSaldoPendingPlatform ?? 0, 0, ',', '.')],
                ['title' => 'Saldo Platform', 'value' => 'Rp' . number_format($totalSaldoPlatform ?? 0, 0, ',', '.')],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <p class="mb-1 fw-semibold">{{ $stat['title'] }}</p>
                        <h3 class="mb-0">{{ $stat['value'] }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
        <div class="row">
            <div class="col-lg-8 d-flex flex-column">
                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="d-sm-flex justify-content-between align-items-start">
                                    <div>
                                        <h4 class="card-title card-title-dash">Manajemen Penjual</h4>
                                        <p class="card-subtitle card-subtitle-dash">Kelola semua permintaan penjual</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-success btn-sm"
                                            type="button"></i>Lihat Semua
                                            Penjual</a>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Akun Seller</th>
                                                <th>Nama Toko</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($sellers as $seller)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex">
                                                            <img class="img-xs rounded-circle"
                                                                src="{{ asset('storage/seller/profile/' . $seller->profile_image) }}"
                                                                alt="Profile image">
                                                            <div>
                                                                <h6>{{ $seller->user->name ?? 'N/A' }}</h6>
                                                                <p>{{ $seller->contact_email }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6>{{ $seller->brand_name }}</h6>
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="badge
                                                            {{ $seller->status == 'pending' ? 'bg-warning' : ($seller->status == 'active' ? 'bg-success' : 'bg-danger') }}">
                                                            {{ ucfirst($seller->status) }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($seller->status == 'pending')
                                                            <form action="{{ route('admin.sellers.verify', $seller->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-outline-success btn-sm">Terima</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('admin.sellers.deactivate', $seller->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-sm">Tolak</button>
                                                            </form>
                                                        @elseif ($seller->status == 'active')
                                                            <form
                                                                action="{{ route('admin.sellers.deactivate', $seller->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-outline-danger btn-sm">Nonaktifkan</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('admin.sellers.setPending', $seller->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-outline-warning btn-sm">Set
                                                                    Menunggu</button>
                                                            </form>
                                                        @elseif ($seller->status == 'inactive')
                                                            <form action="{{ route('admin.sellers.verify', $seller->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-outline-success btn-sm">Aktifkan</button>
                                                            </form>
                                                            <form
                                                                action="{{ route('admin.sellers.setPending', $seller->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-outline-warning btn-sm">Set
                                                                    Menunggu</button>
                                                            </form>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada data penjual.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex flex-column">
                <div class="row flex-grow">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card card-rounded">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h4 class="card-title card-title-dash">Penjual Teratas</h4>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            @forelse ($topSellers as $topSeller)
                                                <div
                                                    class="wrapper d-flex align-items-center justify-content-between py-2 border-bottom">
                                                    <div class="d-flex">
                                                        <img class="img-xs rounded-circle"
                                                            src="{{ asset('storage/seller/profile/' . $topSeller->profile_image) }}"
                                                            alt="Profile image">
                                                        <div class="wrapper ms-3">
                                                            <p class="ms-1 mb-1 fw-bold">
                                                                {{ $topSeller->user->name ?? 'N/A' }}</p>
                                                            <small
                                                                class="text-muted mb-0">{{ $topSeller->brand_name }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="text-muted text-small">
                                                        {{ $topSeller->updated_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center">Tidak ada penjual teratas.</div>
                                            @endforelse
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
