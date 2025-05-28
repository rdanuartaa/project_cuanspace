@extends('layouts.main')
@section('title', 'Login')
@section('content')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="customer-page">
                        <div class="product-related">
                            <div class="container container-42">
                                <h3 class="title text-center">Login</h3>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('login') }}" class="form-customer form-login">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Username or email address *</label>
                                <input type="email" class="form-control form-account" id="email" name="email"
                                    :value="old('email')" required autofocus autocomplete="username" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control form-account" id="password" name="password"
                                    required autocomplete="current-password" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                <label class="form-check-label" for="remember_me">Remember me</label>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-check">
                                <button type="submit" class="btn-login hover-white">Login</button>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="lost-password">Lost your password?</a>
                                @endif
                            </div>
                        </form>

                        <span class="divider"></span>
                        <a href="{{ route('register') }}" class="btn link-button create-account hover-black">Create an
                            account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
