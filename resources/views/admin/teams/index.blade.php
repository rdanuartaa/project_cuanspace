@extends('layouts.admin')

@section('title', 'Manage Teams')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Manage Teams</h4>
            <a href="{{ route('admin.teams.create') }}" class="btn btn-outline-primary mb-3">Add New Team</a>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team)
                            <tr>
                                <td>
                                    <!-- Square image style -->
                                    <img src="{{ asset('storage/' . $team->image) }}" alt="Image" class="team-img">
                                </td>
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->role }}</td>
                                <td>
                                    <a href="{{ route('admin.teams.edit', $team->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>
                                    <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-dark btn-sm">Delete</button>
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
@endsection

@push('styles')
<style>
    .btn-outline-dark {
        color: #000;
        border-color: #000;
        background-color: #fff;
        font-size: 14px;
        border-radius: 20px;
        padding: 10px 15px;
        text-transform: uppercase;
        transition: all 0.3s;
    }

    .btn-outline-dark:hover {
        background-color: #000;
        color: white;
    }

    /* Style for the team image (Square) */
    .team-img {
        width: 150px; /* Increase the width */
        height: 150px; /* Set the height same as the width for square shape */
        object-fit: cover; /* Maintain aspect ratio while covering the box */
        border-radius: 0%; /* No rounded corners, making it a square */
    }
</style>
@endpush
