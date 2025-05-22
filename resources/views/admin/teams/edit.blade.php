@extends('layouts.admin')

@section('title', 'Edit Team')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Team</h4>
            <form action="{{ route('admin.teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $team->name }}" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" class="form-control" id="role" name="role" value="{{ $team->role }}" required>
                </div>

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control custom-file-input" id="image" name="image">
                    <img src="{{ asset('storage/' . $team->image) }}" alt="Image" width="100" class="mt-2">
                </div>

                <button type="submit" class="btn btn-outline-dark">Update Teams</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Style for the custom file input button */
    .custom-file-input {
        border-radius: 20px;
        padding: 8px 20px;
        font-size: 14px;
        color: #000;
        background-color: #fff;
        border: 1px solid #000;
    }

    .custom-file-input:hover {
        background-color: #000;
        color: #fff;
    }

    /* Style for the submit button (white background, black text) */
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
</style>
@endpush
