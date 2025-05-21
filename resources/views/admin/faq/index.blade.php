@extends('layouts.admin')

@section('title', 'Kelola FAQ')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="m-0">Kelola FAQ</h4>
                    <a href="{{ route('admin.faq.create') }}" class="btn btn-sm btn-primary {{ session('success') ? 'btn-success' : '' }}">Tambah FAQ Baru</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @forelse($faqs as $index => $item)
                        <div class="faq-content mb-4 p-4 border rounded bg-light">
                            <h5 class="font-weight-bold">{{ $index + 1 + ($faqs->currentPage() - 1) * $faqs->perPage() }}. {{ $item->question }}</h5>
                            <p class="text-muted mt-2">{!! nl2br(e($item->answer)) !!}</p>
                            <div class="d-flex mt-2">
                                <a href="{{ route('admin.faq.edit', $item->id) }}" class="btn btn-sm btn-outline-primary me-1 btn-view">Edit</a>
                                <form action="{{ route('admin.faq.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger btn-delete">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">Tidak ada FAQ</div>
                    @endforelse
                    <div class="pagination mt-6">
                        {{ $faqs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-view:hover {
        color: white !important;
        background-color: #007bff !important;
    }
    .btn-view {
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }
    .pagination {
        display: flex;
        justify-content: center;
    }
</style>
@endpush

@push('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus FAQ ini?')) {
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush
