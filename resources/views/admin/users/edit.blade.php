@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-lg">
        <div class="card-body">
            <h4 class="card-title text-primary">Edit Pengguna</h4>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="1" {{ $user->email_verified_at ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$user->email_verified_at ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success btn-block">Simpan Perubahan</button>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-light btn-block">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
        border: 1px solid #f1f1f1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 24px;
        font-weight: 600;
    }

    .form-group label {
        font-weight: 500;
        margin-bottom: 5px;
    }

    .form-control {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Button Styles */
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-success:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #ccc;
        color: #007bff;
        font-size: 16px;
        padding: 10px 20px;
        border-radius: 8px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-light:hover {
        background-color: #e2e6ea;
        transform: translateY(-2px);
    }

    /* Focus Styles */
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
    }

    .btn-block {
        width: 100%;
    }
</style>
@endpush
