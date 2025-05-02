<!-- resources/views/main/seller-register.blade.php -->

@extends('layouts.main')

@section('title', 'Seller Register')

@section('content')
<div class="main-content space-padding-tb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="customer-page">
                    <div class="title-page">
                        <h3>Become a Seller</h3>
                    </div>

                    <!-- Menampilkan pesan status -->
                    @if(session('status'))
                        <div class="alert alert-info">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('seller.register.submit') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Brand Name -->
                        <div class="form-group">
                            <label for="brand_name">Brand Name *</label>
                            <input type="text" class="form-control form-account" id="brand_name" name="brand_name" required />
                            @error('brand_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea class="form-control form-account" id="description" name="description" required></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contact Email -->
                        <div class="form-group">
                            <label for="contact_email">Contact Email *</label>
                            <input type="email" class="form-control form-account" id="contact_email" name="contact_email" required />
                            @error('contact_email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Contact WhatsApp -->
                        <div class="form-group">
                            <label for="contact_whatsapp">Contact WhatsApp *</label>
                            <input type="text" class="form-control form-account" id="contact_whatsapp" name="contact_whatsapp" required />
                            @error('contact_whatsapp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Profile Image -->
                        <div class="form-group">
                            <label for="profile_image">Profile Image *</label>
                            <input type="file" class="form-control form-account" id="profile_image" name="profile_image" required />
                            @error('profile_image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Banner Image -->
                        <div class="form-group">
                            <label for="banner_image">Banner Image *</label>
                            <input type="file" class="form-control form-account" id="banner_image" name="banner_image" required />
                            @error('banner_image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn-login hover-white">Register as Seller</button>
                    </form>

                    <span class="divider"></span>
                    <a href="{{ route('profile.edit') }}" class="btn link-button create-account hover-black">Back to Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
