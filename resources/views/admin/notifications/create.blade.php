@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <h2 class="mb-4 text-center">Tambah Notifikasi</h2>

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

            @if (session('success'))
                <div class="alert alert-success rounded fade show" role="alert" style="transition: opacity 1s;">
                    {{ session('success') }}
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

            @if (session('error'))
                <div class="alert alert-danger rounded fade show" role="alert" style="transition: opacity 1s;">
                    {{ session('error') }}
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

            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Masukkan judul notifikasi" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Pesan</label>
                    <textarea name="pesan" id="pesan" class="form-control @error('pesan') is-invalid @enderror" rows="4" placeholder="Masukkan pesan notifikasi" required>{{ old('pesan') }}</textarea>
                    @error('pesan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Penerima</label>
                    <select name="penerima" id="penerima" class="form-control @error('penerima') is-invalid @enderror" onchange="toggleKhususOptions()" required>
                        <option value="semua" {{ old('penerima') == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="pengguna" {{ old('penerima') == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                        <option value="seller" {{ old('penerima') == 'seller' ? 'selected' : '' }}>Seller</option>
                        <option value="khusus" {{ old('penerima') == 'khusus' ? 'selected' : '' }}>Khusus</option>
                    </select>
                    @error('penerima')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="khusus_section" style="display:none;">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Penerima Khusus</label>
                        <select name="recipient_type" id="recipient_type" class="form-control" onchange="toggleRecipientFields()">
                            <option value="">-- Pilih Tipe Penerima --</option>
                            <option value="user" {{ old('recipient_type') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="seller" {{ old('recipient_type') == 'seller' ? 'selected' : '' }}>Seller</option>
                        </select>
                    </div>

                    <div class="mb-3" id="user_select" style="display:none;">
                        <label class="form-label fw-bold">Pilih User</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} (ID: {{ $user->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="seller_select" style="display:none;">
                        <label class="form-label fw-bold">Pilih Seller</label>
                        <select name="seller_id" id="seller_id" class="form-control @error('seller_id') is-invalid @enderror">
                            <option value="">-- Pilih Seller --</option>
                            @foreach($sellers as $seller)
                                <option value="{{ $seller->user->id }}" {{ old('seller_id') == $seller->user->id ? 'selected' : '' }}>
                                    {{ $seller->brand_name }} (ID: {{ $seller->user->id }})
                                </option>
                            @endforeach
                        </select>
                        @error('seller_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="terkirim" {{ old('status') == 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleKhususOptions() {
        const penerima = document.getElementById('penerima').value;
        const khususSection = document.getElementById('khusus_section');
        khususSection.style.display = penerima === 'khusus' ? 'block' : 'none';

        // Reset field ketika penerima bukan khusus
        if (penerima !== 'khusus') {
            document.getElementById('recipient_type').value = '';
            document.getElementById('user_select').style.display = 'none';
            document.getElementById('seller_select').style.display = 'none';
            document.getElementById('user_id').value = '';
            document.getElementById('seller_id').value = '';
        }
    }

    function toggleRecipientFields() {
        const recipientType = document.getElementById('recipient_type').value;
        const userSelect = document.getElementById('user_select');
        const sellerSelect = document.getElementById('seller_select');
        const userId = document.getElementById('user_id');
        const sellerId = document.getElementById('seller_id');

        userSelect.style.display = recipientType === 'user' ? 'block' : 'none';
        sellerSelect.style.display = recipientType === 'seller' ? 'block' : 'none';

        // Reset field yang tidak dipilih
        if (recipientType === 'user') {
            sellerId.value = '';
        } else if (recipientType === 'seller') {
            userId.value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleKhususOptions();
        toggleRecipientFields();
    });
</script>
@endsection
