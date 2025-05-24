@extends('layouts.admin')

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success rounded fade show" role="alert" style="transition: opacity 1s;">
            {{ session('success') }}
            <script>
                setTimeout(function() {
                    let alert = document.querySelector('.alert');
                    alert.style.opacity = 0;
                    setTimeout(function() {
                        alert.remove();
                    }, 1000);
                }, 3000);
            </script>
        </div>
    @endif

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Kelola Notifikasi</h4>
                    <a href="{{ route('admin.notifications.create') }}" class="btn btn-outline-success btn-sm">Tambah Notifikasi</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Pesan</th>
                                <th>Penerima</th>
                                <th>User Khusus</th>
                                <th>Seller Khusus</th>
                                <th>Status</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $notification->judul }}</td>
                                    <td>{{ Str::limit($notification->pesan, 50) }}</td>
                                    <td>{{ ucfirst($notification->penerima) }}</td>
                                    <td>
                                        @if($notification->penerima === 'khusus' && $notification->user)
                                            {{ $notification->user->name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->penerima === 'khusus' && $notification->seller)
                                            {{ $notification->seller->brand_name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($notification->status == 'terkirim')
                                            <span class="badge bg-success">Terkirim</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($notification->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $notification->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus notifikasi?')" class="btn btn-outline-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data notifikasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection