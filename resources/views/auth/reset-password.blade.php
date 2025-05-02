@extends('layouts.main')
@section('title', 'Reset Password')
@section('content')
    <div class="main-content space-padding-tb-70">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="customer-page">
                        <div class="title-page">
                            <h3>Reset Your Password</h3>
                        </div>

                        <form method="POST" action="{{ route('password.store') }}" class="form-customer form-login">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" class="form-control form-account" id="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">New Password *</label>
                                <input type="password" class="form-control form-account" id="password" name="password" required autocomplete="new-password" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password *</label>
                                <input type="password" class="form-control form-account" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" />
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-check">
                                <button type="submit" class="btn-login hover-white">Reset Password</button>
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
