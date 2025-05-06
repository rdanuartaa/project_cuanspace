@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center">Tambah Kategori</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Masukkan nama kategori" required>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-outline-info btn-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
