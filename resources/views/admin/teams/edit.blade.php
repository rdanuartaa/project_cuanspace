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
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $team->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <input type="text" class="form-control" id="role" name="role" value="{{ old('role', $team->role) }}" required>
                </div>

                <div class="form-group">
                    <label for="image">Image</label><br>
                    @if ($team->image)
                        <img src="{{ asset('storage/' . $team->image) }}" alt="Current Image" width="100" class="mb-2">
                    @else
                        <span class="text-muted d-block mb-2">Tidak ada foto</span>
                    @endif
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Preview Gambar Baru -->
                <div class="form-group mt-3">
                    <label>Preview Gambar Baru</label><br>
                    <img id="preview" src="#" alt="Preview Image" style="display: none; max-height: 200px;" />
                </div>

                <button type="submit" class="btn btn-outline-dark">Update Team</button>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection

@endsection
