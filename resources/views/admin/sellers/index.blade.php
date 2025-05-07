<!-- resources/views/admin/sellers/index.blade.php -->

@extends('layouts.admin')

@section('title', 'Seller Management')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Seller Management</h4>

            <!-- Filter dan Sort -->
            <div class="mb-3">
                <form method="GET" action="{{ route('admin.sellers.index') }}" class="d-flex gap-3">
                    <div>
                        <label for="status" class="form-label">Filter Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort" class="form-label">Sort By</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>
                    <div class="align-self-end">
                        <button type="submit" class="btn btn-primary">Apply</button>
                    </div>
                </form>
            </div>

            <!-- Tabel Seller -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Brand Name</th>
                            <th>Status</th>
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
                                    <span class="badge
                                        {{ $seller->status == 'pending' ? 'badge-opacity-warning' :
                                           ($seller->status == 'active' ? 'badge-opacity-success' : 'badge-opacity-danger') }}">
                                        {{ ucfirst($seller->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($seller->status == 'pending')
                                        <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Verify</button>
                                        </form>
                                        <form action="{{ route('admin.sellers.deactivate', $seller->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    @elseif ($seller->status == 'active')
                                        <form action="{{ route('admin.sellers.deactivate', $seller->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Deactivate</button>
                                        </form>
                                        <form action="{{ route('admin.sellers.setPending', $seller->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Set Pending</button>
                                        </form>
                                    @elseif ($seller->status == 'inactive')
                                        <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Activate</button>
                                        </form>
                                        <form action="{{ route('admin.sellers.setPending', $seller->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm">Set Pending</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
