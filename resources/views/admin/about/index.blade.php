@extends('layouts.admin')

@section('content')
<div class="container">
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
                }, 1000);
            }, 3000);
        </script>
    @endif

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Kelola About</h4>
                    <a href="{{ route('admin.about.create') }}" class="btn btn-outline-success btn-sm">Tambah About</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Deskripsi</th>
                                <th>Thumbnail</th>
                                <th>Visi</th>
                                <th>Misi</th>
                                <th>Status</th>  <!-- Tambahan kolom Status -->
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($abouts as $about)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $about->judul }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($about->deskripsi, 50) }}</td>
                                    <td>
                                        @if ($about->thumbnail)
                                            <img src="{{ asset('storage/' . $about->thumbnail) }}" alt="Thumbnail" class="thumbnail-img">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ \Illuminate\Support\Str::limit($about->visi, 50) }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($about->misi, 50) }}</td>
                                    <td>
                                        @if ($about->status == 'Published')
                                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-sm">Published</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-sm">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.about.edit', $about->id) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                        <form action="{{ route('admin.about.destroy', $about->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus data About?')" class="btn btn-outline-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada data About.</td>
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