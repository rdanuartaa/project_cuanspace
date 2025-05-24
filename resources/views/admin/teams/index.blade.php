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
                                        @if ($team->image)
                                            <img src="{{ asset('storage/' . $team->image) }}"
                                                 alt="Foto {{ $team->name }}"
                                                 style="width: 80px; height: 100px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="text-muted">Tidak ada foto</span>
                                        @endif
                                    </td>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ $team->role }}</td>
                                    <td>
                                        <a href="{{ route('admin.teams.edit', $team->id) }}"
                                            class="btn btn-outline-dark btn-sm">Edit</a>
                                        <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST"
                                            class="d-inline">
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
