@extends('layouts.seller')

@section('content')
    <div class="card">
        <div class="card-body">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Profil Toko</h2>

            <!-- Alert Success -->
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-6 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('seller.pengaturan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Nama Brand -->
                <div class="mb-3">
                    <label class="form-label fw-bold block font-bold text-gray-700 mb-1">Nama Brand</label>
                    <input type="text" name="brand_name"
                           class="form-control w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ old('brand_name', $seller->brand_name) }}" required>
                    @error('brand_name')
                        <span class="text-danger text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-3">
                    <label class="form-label fw-bold block font-bold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="form-control w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $seller->description) }}</textarea>
                    @error('description')
                        <span class="text-danger text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Kontak -->
                <div class="mb-3">
                    <label class="form-label fw-bold block font-bold text-gray-700 mb-1">Email Kontak</label>
                    <div class="input-group flex">
                        <span class="input-group-text bg-gray-200 border border-gray-300 px-3 py-2 rounded-l">✉️</span>
                        <input type="email" name="contact_email"
                               class="form-control w-full border border-gray-300 rounded-r px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('contact_email', $seller->contact_email) }}" required>
                    </div>
                    @error('contact_email')
                        <span class="text-danger text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- No WhatsApp -->
                <div class="mb-3">
                    <label class="form-label fw-bold block font-bold text-gray-700 mb-1">No WhatsApp</label>
                    <div class="input-group flex">
                        <span class="input-group-text bg-gray-200 border border-gray-300 px-3 py-2 rounded-l">+62</span>
                        <input type="text" name="contact_whatsapp"
                               class="form-control w-full border border-gray-300 rounded-r px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('contact_whatsapp', $seller->contact_whatsapp) }}">
                    </div>
                    @error('contact_whatsapp')
                        <span class="text-danger text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Foto Profil -->
                <div class="mb-3">
                    <label class="form-label fw-bold block font-bold text-gray-700 mb-1">Foto Profil</label>
                    @if($seller->profile_image)
                        <img src="{{ asset('storage/' . $seller->profile_image) }}" alt="Profil Toko"
                             class="w-24 h-24 object-cover rounded-full border border-gray-300 mb-2">
                    @endif
                    <input type="file" name="profile_image"
                           class="form-control block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('profile_image')
                        <span class="text-danger text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Banner Toko -->
                <div class="mb-3">
                    <label class="form-label fw-bold block font-bold text-gray-700 mb-1">Banner Toko</label>
                    @if($seller->banner_image)
                        <img src="{{ asset('storage/' . $seller->banner_image) }}" alt="Banner Toko"
                             class="w-full max-w-md h-32 object-cover rounded border border-gray-300 mb-2">
                    @endif
                    <input type="file" name="banner_image"
                           class="form-control block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('banner_image')
                        <span class="text-danger text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="text-end mt-3">
                    <button type="submit"
                            class="btn btn-outline-success btn-sm transition duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
