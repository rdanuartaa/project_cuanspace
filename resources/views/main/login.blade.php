@extends('layouts.main')
@section('content')
<div class="main-content space-padding-tb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="customer-page">
                    <div class="title-page">
                        <h3>Login</h3>
                    </div>
                    <form method="post" class="form-customer form-login">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Username or email address *</label>
                            <input type="email" class="form-control form-account" id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password *</label>
                            <input type="password" class="form-control form-account" id="exampleInputPassword1">
                        </div>
                        <div class="form-check">
                            <button type="submit" class="btn-login hover-white">Login</button>
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input">
                                <span>Remember me</span>
                            </label>
                            <a href="" class="lost-password">Lost your password?</a>
                        </div>
                    </form>
                    <span class="divider"></span>
                    <a href="" class="btn link-button create-account hover-black">Create an account</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
