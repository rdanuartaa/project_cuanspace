@extends('layouts.main')
@section('title', 'Confirm Password')
@section('content')
    <div class="main-content space-padding-tb-70">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <div class="customer-page">
                        <div class="title-page">
                            <h3>Confirm Your Password</h3>
                        </div>

                        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}" class="form-customer form-login">
                            @csrf

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password">Password *</label>
                                <input type="password" class="form-control form-account" id="password" name="password" required autocomplete="current-password" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-check">
                                <button type="submit" class="btn-login hover-white">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
