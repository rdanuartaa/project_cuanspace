@extends('layouts.seller')
@section('title', 'Saldo Saya')

@section('content')
    <div class="row">
        <!-- Kolom 1: Saldo Saat Ini dan Form Penarikan -->
        <div class="col-md-6">
            <div class="card mb-3 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <h4 class="card-title mb-3">Saldo Saya</h4>

                        <!-- Mulai dibungkus agar isinya rata tengah -->
                        <div class="text-center">
                            <!-- Saldo -->
                            <div class="mb-4">
                                <strong>Saldo Saat Ini:</strong><br>
                                <span class="text-success text-2xl fw-bold">
                                    Rp{{ number_format($currentBalance, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Form & Alert -->
                            <div class="d-flex justify-content-center">
                                <div style="max-width: 300px; width: 100%;">
                                    @if ($seller->bank_name && $seller->bank_account && $currentBalance > 0)
                                        <form action="{{ route('seller.saldo.tarik') }}" method="POST"
                                            class="mb-3 d-flex flex-column gap-2">
                                            @csrf
                                            <input type="number" name="amount" class="form-control text-center w-100"
                                                placeholder="Masukkan nominal penarikan (min Rp10.000)" min="10000"
                                                required>
                                            <button type="submit" class="btn btn-success text-white w-100">Tarik
                                                Saldo</button>
                                        </form>
                                    @elseif($currentBalance > 0)
                                        <div class="alert alert-warning text-center mb-3">
                                            Silakan lengkapi informasi rekening bank terlebih dahulu sebelum menarik saldo.
                                        </div>
                                    @endif

                                    <!-- Pesan Sukses / Error -->
                                    @if (session('success'))
                                        <div class="alert alert-success text-center">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger text-center">{{ session('error') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Akhir pembungkus tengah -->
                    </div>
                </div>
            </div>
        </div>


        <!-- Kolom 2: Rekening Bank -->
        <div class="col-md-6">
            <div class="card mb-3 h-100">
                <div class="card-body">
                    <h4 class="card-title mb-3">Rekening Bank</h4>

                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Pastikan Anda mengisi <strong>Nama Bank</strong> dan <strong>Nomor
                            Rekening</strong> dengan benar dan sesuai. Kesalahan pengisian dapat menyebabkan kegagalan atau
                        keterlambatan saat penarikan dana.
                    </div>

                    <form action="{{ route('seller.saldo.updateBank') }}" method="POST" class="d-flex flex-column gap-3">
                        @csrf
                        <div class="d-flex gap-3 w-100">
                            <!-- Input Nama Bank -->
                            <div style="flex: 1;">
                                <label for="bank_name" class="form-label">Nama Bank</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control"
                                    value="{{ old('bank_name', $seller->bank_name) }}"
                                    placeholder="Contoh: BCA, BRI, Mandiri" required>
                            </div>

                            <!-- Input Nomor Rekening -->
                            <div style="flex: 1;">
                                <label for="bank_account" class="form-label">Nomor Rekening</label>
                                <input type="text" name="bank_account" id="bank_account" class="form-control"
                                    value="{{ old('bank_account', $seller->bank_account) }}"
                                    placeholder="Masukkan nomor rekening" required>
                            </div>
                        </div>

                        <!-- Tombol Submit full width -->
                        <div>
                            <button type="submit" class="btn btn-success text-white w-100">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Penarikan (Full Width) -->
    <div class="card mt-3">
        <div class="card-body">
            <h4 class="card-title mb-4">Riwayat Penarikan</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($withdrawHistory as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($item->status) }}</td>
                                <td>
                                    <!-- Tombol buka modal -->
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $item->id }}">
                                        Detail
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Detail -->
                            <!-- Modal Detail -->
                            <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1"
                                aria-labelledby="detailModalLabel{{ $item->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="detailModalLabel{{ $item->id }}">Detail
                                                Penarikan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Tanggal:</strong> {{ $item->created_at->format('d M Y H:i') }}</p>
                                            <p><strong>Jumlah:</strong> Rp{{ number_format($item->amount, 0, ',', '.') }}
                                            </p>
                                            <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>

                                            @if ($item->status === 'success')
                                                <p class="text-success fw-semibold">Penarikan berhasil. Dana sudah masuk ke
                                                    rekening Anda.</p>
                                            @endif

                                            @if ($item->admin_note)
                                                <p class="mt-3 text-info">
                                                    <strong>Catatan Admin:</strong> {{ $item->admin_note }}
                                                </p>
                                            @else
                                                <p class="mt-3 text-info">
                                                    Penarikan berhasil. Dana sudah masuk ke
                                                    rekening Anda.
                                                </p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada riwayat penarikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $withdrawHistory->links() }}
            </div>
        </div>
    </div>
@endsection
