@extends('layouts.main')
@section('title', 'Profile')
@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="customer-page">
                        <div class="product-related">
                            <div class="container container-42">
                                <h3 class="title text-center">Profile Saya</h3>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('profile.update') }}"class="form-customer form-login">
                            @csrf
                            @method('PATCH')

                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control form-account" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required autofocus />
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" class="form-control form-account" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn-login hover-white">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
