@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Edit Kategori</h2>
    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" required>
        </div>
        <button type="submit" class="btn btn-outline-warning btn-sm mt-2">Update</button>
    </form>
</div>
@endsection
