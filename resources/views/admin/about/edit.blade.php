@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center">Edit About</h2>

            <form action="{{ route('admin.about.update', $about->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Judul</label>
                    <input type="text" name="judul" class="form-control" value="{{ $about->judul }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required>{{ $about->deskripsi }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Thumbnail</label>
                    
                    @if ($about->thumbnail)
                        <div>
                            <img src="{{ asset('storage/' . $about->thumbnail) }}" alt="Thumbnail" 
                                 style="max-width: 200px; height: auto; display: block; margin-bottom: 10px;">
                        </div>
                    @endif

                    <input type="file" name="thumbnail" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Visi</label>
                    <textarea name="visi" class="form-control" rows="2" required>{{ $about->visi }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Misi</label>
                    <textarea name="misi" class="form-control" rows="2" required>{{ $about->misi }}</textarea>
                </div>

                <!-- Form untuk Status -->
                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="Published" {{ old('status', $about->status ?? '') == 'Published' ? 'selected' : '' }}>Published</option>
                        <option value="Draft" {{ old('status', $about->status ?? '') == 'Draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-outline-warning btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
