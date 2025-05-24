@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center">Edit Notifikasi</h2>

            @if ($errors->any())
                <div class="alert alert-danger rounded fade show" role="alert" style="transition: opacity 1s;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>❌ {{ $error }}</li>
                        @endforeach
                    </ul>
                    <script>
                        setTimeout(function() {
                            let alert = document.querySelector('.alert');
                            alert.style.opacity = 0;
                            setTimeout(function() {
                                alert.remove();
                            }, 1000);
                        }, 3000);
                    </script>
                </div>
            @endif

            <form action="{{ route('admin.notifications.update', $notification->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $notification->judul) }}" placeholder="Masukkan judul notifikasi" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pesan</label>
                    <textarea name="pesan" id="pesan" class="form-control @error('pesan') is-invalid @enderror" rows="4" placeholder="Masukkan pesan notifikasi" required>{{ old('pesan', $notification->pesan) }}</textarea>
                    @error('pesan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Penerima</label>
                    <select name="penerima" id="penerima" class="form-control @error('penerima') is-invalid @enderror" onchange="toggleKhususFields()" required>
                        <option value="semua" {{ old('penerima', $notification->penerima) == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="user" {{ old('penerima', $notification->penerima) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="seller" {{ old('penerima', $notification->penerima) == 'seller' ? 'selected' : '' }}>Seller</option>
                        <option value="khusus" {{ old('penerima', $notification->penerima) == 'khusus' ? 'selected' : '' }}>Khusus</option>
                    </select>
                    @error('penerima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="khusus_type_section">
                    <label class="form-label fw-bold">Pilih Khusus</label>
                    <select name="khusus_type" id="khusus_type" class="form-control @error('khusus_type') is-invalid @enderror" onchange="toggleKhususFields()">
                        <option value="">-- Pilih Khusus Type --</option>
                        <option value="user" {{ old('khusus_type', $notification->khusus_type) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="seller" {{ old('khusus_type', $notification->khusus_type) == 'seller' ? 'selected' : '' }}>Seller</option>
                    </select>
                    @error('khusus_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="user_select">
                    <label class="form-label fw-bold">Pilih User</label>
                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                        <option value="">-- Pilih User --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $notification->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} (ID: {{ $user->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="seller_select">
                    <label class="form-label fw-bold">Pilih Seller</label>
                    <select name="seller_brand_name" id="seller_brand_name" class="form-control @error('seller_brand_name') is-invalid @enderror">
                        <option value="">-- Pilih Seller --</option>
                        @foreach($sellers as $seller)
                            <option value="{{ $seller->brand_name }}" {{ old('seller_brand_name', $notification->seller_brand_name) == $seller->brand_name ? 'selected' : '' }}>
                                {{ $seller->brand_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('seller_brand_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-outline-warning btn-sm">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleKhususFields() {
        const penerima = document.getElementById('penerima').value;
        const khususTypeSection = document.getElementById('khusus_type_section');
        const userSelect = document.getElementById('user_select');
        const sellerSelect = document.getElementById('seller_select');
        const khususType = document.getElementById('khusus_type').value;

        khususTypeSection.style.display = penerima === 'khusus' ? 'block' : 'none';
        userSelect.style.display = penerima === 'khusus' && khususType === 'user' ? 'block' : 'none';
        sellerSelect.style.display = penerima === 'khusus' && khususType === 'seller' ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleKhususFields();
        document.getElementById('penerima').addEventListener('change', toggleKhususFields);
        document.getElementById('khusus_type').addEventListener('change', toggleKhususFields);
    });
</script>
@endsection