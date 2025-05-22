@extends('layouts.admin')

@section('title', 'Kelola FAQ')

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
                }, 1000);  // Menghapus notifikasi setelah animasi selesai
            }, 3000);  // Hilang setelah 3 detik
        </script>
    @endif

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title">Kelola FAQ</h4>
                    <a href="{{ route('admin.faq.create') }}" class="btn btn-outline-success btn-sm">Tambah FAQ Baru</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pertanyaan</th>
                                <th>Jawaban</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faqs as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 + ($faqs->currentPage() - 1) * $faqs->perPage() }}</td>
                                    <td>{{ $item->question }}</td>
                                    <td>{!! nl2br(e($item->answer)) !!}</td>
                                    <td>
                                        <a href="{{ route('admin.faq.edit', $item->id) }}" class="btn btn-outline-info btn-sm">Edit</a>
                                        <form action="{{ route('admin.faq.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin hapus FAQ?')" class="btn btn-outline-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Belum ada data FAQ.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $faqs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
