<!-- resources/views/admin/sellers/index.blade.php -->

@extends('layouts.admin')

@section('title', 'Seller Management')

@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Seller Management</h4>

                <!-- Filter Status -->
                <div class="mb-3">
                    <div>
                        <label for="status" class="form-label">Filter Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Tabel Seller -->
                <div class="table-responsive">
                    <table class="table" id="seller-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Brand Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="seller-table-body">
                            @foreach ($sellers as $seller)
                                <tr>
                                    <td>{{ $seller->user->name }}</td>
                                    <td>{{ $seller->contact_email }}</td>
                                    <td>{{ $seller->brand_name }}</td>
                                    <td>
                                        <span
                                            class="badge
                                        {{ $seller->status == 'pending'
                                            ? 'badge-opacity-warning'
                                            : ($seller->status == 'active'
                                                ? 'badge-opacity-success'
                                                : 'badge-opacity-danger') }}">
                                            {{ ucfirst($seller->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($seller->status == 'pending')
                                            <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-success btn-sm">Terima</button>
                                            </form>
                                            <form action="{{ route('admin.sellers.deactivate', $seller->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Tolak</button>
                                            </form>
                                        @elseif ($seller->status == 'active')
                                            <form action="{{ route('admin.sellers.deactivate', $seller->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm">Nonaktifkan</button>
                                            </form>
                                            <form action="{{ route('admin.sellers.setPending', $seller->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-sm">Set
                                                    Menunggu</button>
                                            </form>
                                        @elseif ($seller->status == 'inactive')
                                            <form action="{{ route('admin.sellers.verify', $seller->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-outline-success btn-sm">Aktifkan</button>
                                            </form>
                                            <form action="{{ route('admin.sellers.setPending', $seller->id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-sm">Set
                                                    Menunggu</button>
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

    <!-- Include jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Listen for changes in the status dropdown
            $('#status').on('change', function() {
                var status = $(this).val();

                // Send AJAX request to fetch filtered sellers
                $.ajax({
                    url: '{{ route('admin.sellers.filter') }}',
                    method: 'GET',
                    data: {
                        status: status
                    },
                    success: function(response) {
                        // Update the table body with the new data
                        $('#seller-table-body').html(response.html);
                    },
                    error: function(xhr) {
                        alert('An error occurred while fetching data.');
                    }
                });
            });
        });
    </script>
@endsection
