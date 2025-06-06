@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="card-title text-dark font-weight-bold">Kelola Pengguna</h4>
            </div>

            <!-- Success and Error Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Table -->
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tanggal Registrasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr style="background-color: #ffffff;"> <!-- Ensures all rows have a white background -->
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin hapus pengguna?')" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data pengguna.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        border: 1px solid #f1f1f1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 26px;
        font-weight: 600;
    }

    .table th, .table td {
        vertical-align: middle;
        border: none; /* No borders for the table */
        background-color: #ffffff; /* Set rows background color to white */
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #ffffff; /* White background for odd rows */
    }

    .table-hover tbody tr:hover {
        background-color: #ffffff; /* Ensure no hover color change */
    }

    .alert {
        margin-bottom: 20px;
    }

    .btn-outline-primary {
        border-color: #007bff;
        color: #007bff;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: white;
    }

    .btn-outline-danger {
        border-color: #dc3545;
        color: #dc3545;
        transition: background-color 0.3s, color 0.3s;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
    }

    /* Customize alert dismissal */
    .alert-dismissible .close {
        font-size: 1.2rem;
        color: #000;
    }
</style>
@endpush
