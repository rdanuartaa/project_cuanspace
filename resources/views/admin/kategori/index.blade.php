@extends('layouts.admin')

@section('content')
    @if (session('success'))
        <div class="alert alert-success rounded fade show" role="alert" style="transition: opacity 1s;">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                let alert = document.querySelector('.alert');
                alert.style.opacity = 0;
                setTimeout(function() {
                    alert.remove();
                }, 1000);  // Menghapus notifikasi setelah animasi selesai
            }, 3000);  // Hilang setelah 3 detik
        </script>
    @endif

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Kelola Kategori</h4>
                    <a href="{{ route('admin.kategori.create') }}" class="btn btn-outline-success btn-sm">Tambah Kategori</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead >
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Slug</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kategoris as $kategori)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kategori->nama_kategori }}</td>
                                    <td>{{ $kategori->slug }}</td>
                                    <td>
                                        <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                        <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus kategori?')" class="btn btn-outline-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($kategoris->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data kategori.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
