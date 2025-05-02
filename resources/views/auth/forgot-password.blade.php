@extends('layouts.main')
@section('title', 'Forgot Password')
@section('content')
    <div class="main-content space-padding-tb-70">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="customer-page">
                        <div class="title-page">
                            <h3>Forgot Your Password?</h3>
                        </div>

                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </div>

                        <form method="POST" action="{{ route('password.email') }}" class="form-customer form-login">
                            @csrf

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" class="form-control form-account" id="email" name="email" :value="old('email')" required autofocus />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Submit Button -->
                            <div class="form-check">
                                <button type="submit" class="btn-login hover-white">Email Password Reset Link</button>
                            </div>
                        </form>

                        <span class="divider"></span>
                        <a href="{{ route('login') }}" class="btn link-button create-account hover-black">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
