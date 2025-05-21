@extends('layouts.admin')

@section('title', 'Buat FAQ Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="m-0">Buat FAQ Baru</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('admin.faq.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="question" class="form-label">Pertanyaan</label>
                            <input type="text" class="form-control" id="question" name="question" value="{{ old('question') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="answer" class="form-label">Jawaban</label>
                            <textarea class="form-control" id="answer" name="answer" rows="5" required>{{ old('answer') }}</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-light border {{ session('success') ? 'btn-success text-white' : '' }}">Simpan FAQ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-light:hover {
        background-color: #f8f9fa;
        border-color: #d3d4d5;
    }
</style>
@endpush
