@extends('layouts.admin')
@section('content')
    <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
            <div class="row">
                <div class="col-sm-12">
                    <div class="statistics-details d-flex align-items-center justify-content-between">
                        <div>
                            <p class="statistics-title">Total Penjual</p>
                            <h3 class="rate-percentage">{{ $totalSeller ?? 0 }}</h3>
                        </div>
                        <div>
                            <p class="statistics-title">Total Produk</p>
                            <h3 class="rate-percentage">{{ $totalProduk ?? 0 }}</h3>
                        </div>
                        <div>
                            <p class="statistics-title">Total Transaksi Berhasil</p>
                            <h3 class="rate-percentage">{{ $totalTransaksiBerhasil ?? 0 }}</h3>
                        </div>
                        <div>
                            <p class="statistics-title">Total Saldo Platform</p>
                            <h3 class="rate-percentage">Rp {{ number_format($totalSaldoPlatform ?? 0, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
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
                                            <a href="{{ route('admin.sellers.index') }}"
                                                class="btn btn-primary btn-lg text-white mb-0 me-0" type="button"><i
                                                    class="mdi mdi-account-plus"></i>Lihat Semua Penjual</a>
                                        </div>
                                    </div>
                                    <div class="table-responsive mt-1">
                                        <table class="table select-table">
                                            <thead>
                                                <tr>
                                                    <th>Pelanggan</th>
                                                    <th>Merek</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($sellers as $seller)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex">
                                                                <img src="{{ Storage::url($seller->profile_image) }}"
                                                                    alt="profil" class="img-sm rounded-10">
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
                                                            {{ $seller->status == 'pending'
                                                                ? 'badge-opacity-warning'
                                                                : ($seller->status == 'active'
                                                                    ? 'badge-opacity-success'
                                                                    : 'badge-opacity-danger') }}">
                                                                {{ ucfirst($seller->status) }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($seller->status == 'pending')
                                                                <form
                                                                    action="{{ route('admin.sellers.verify', $seller->id) }}"
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
                                                                <form
                                                                    action="{{ route('admin.sellers.verify', $seller->id) }}"
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
                                                            <img class="img-sm rounded-10"
                                                                src="{{ Storage::url($topSeller->profile_image) }}"
                                                                alt="profil">
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
    </div>
@endsection
