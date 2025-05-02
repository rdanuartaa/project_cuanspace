<!-- resources/views/admin/sellers/index.blade.php -->

@extends('layouts.admin')

@section('title', 'Verify Sellers')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pending Sellers</h4>

            <!-- Tabel Seller yang terdaftar dan status pending -->
            <div class="template-demo">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Brand Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sellers as $seller)
                                <tr>
                                    <td>{{ $seller->user->name }}</td>
                                    <td>{{ $seller->contact_email }}</td>
                                    <td>{{ $seller->brand_name }}</td>
                                    <td>
                                        <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Verify</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
