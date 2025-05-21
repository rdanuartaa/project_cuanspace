@extends('layouts.seller')
@section('title', 'Saldo Saya')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-4">Saldo Saya</h4>
            <div class="mb-4 text-center">
                <strong>Saldo Saat Ini:</strong><br>
                <span class="text-green-600 text-2xl font-bold">Rp{{ number_format($currentBalance, 0, ',', '.') }}</span>
            </div>
            @if ($currentBalance > 0)
                <form action="{{ route('seller.saldo.tarik') }}" method="POST"
                    class="mb-4 d-flex justify-content-center gap-2">
                    @csrf
                    <div>
                        <input type="number" name="amount" class="form-control text-center"
                            placeholder="Masukkan nominal penarikan (min Rp10.000)" min="10000" required>
                    </div>
                    <button type="submit" class="btn btn-success text-white">Tarik Saldo</button>
                </form>
            @endif

            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif
            <h5 class="mt-5">Riwayat Penarikan</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($withdrawHistory as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d M Y') }}</td>
                                <td>Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($item->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada riwayat penarikan.</td>
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
