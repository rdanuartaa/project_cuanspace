@extends('layouts.main')
@section('title', 'Register')
@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="customer-page">
                        <div class="product-related">
                            <div class="container container-42">
                                <h3 class="title text-center">Registrasi</h3>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('register') }}" class="form-customer form-login">
                            @csrf

                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" class="form-control form-account" id="name" name="name"
                                    :value="old('name')" required autofocus autocomplete="name" />
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" class="form-control form-account" id="email" name="email"
                                    :value="old('email')" required autocomplete="username" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control form-account" id="password" name="password"
                                    required autocomplete="new-password" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" class="form-control form-account" id="password_confirmation"
                                    name="password_confirmation" required autocomplete="new-password" />
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-check">
                                <button type="submit" class="btn-login hover-white">Register</button>
                            </div>

                            <!-- Already registered? Link -->
                            <div class="form-check">
                                <a href="{{ route('login') }}" class="lost-password">Already registered?</a>
                            </div>
                        </form>

                        <span class="divider"></span>
                        <a href="{{ route('login') }}" class="btn link-button create-account hover-black">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
