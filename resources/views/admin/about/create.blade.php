@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center">Tambah About</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.about.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><label for="judul" class="form-label fw-bold">Judul</label></td>
                            <td>
                                <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul') }}" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="deskripsi" class="form-label fw-bold">Deskripsi</label></td>
                            <td>
                                <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="thumbnail" class="form-label fw-bold">Thumbnail</label></td>
                            <td>
                                <input type="file" name="thumbnail" id="thumbnail" class="form-control" required>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="visi" class="form-label fw-bold">Visi</label></td>
                            <td>
                                <textarea name="visi" id="visi" class="form-control" rows="3" required>{{ old('visi') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="misi" class="form-label fw-bold">Misi</label></td>
                            <td>
                                <textarea name="misi" id="misi" class="form-control" rows="3" required>{{ old('misi') }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-end">
                                <button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
                                <a href="{{ route('admin.about.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection
