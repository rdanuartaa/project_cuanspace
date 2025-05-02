@extends('layouts.main')
@section('content')
<div class="main-content space-padding-tb-70">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div class="customer-page">
                    <div class="title-page">
                        <h3>Register</h3>
                    </div>
                    <form method="post" class="form-customer form-login">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Name *</label>
                            <input type="email" class="form-control form-account" id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address *</label>
                            <input type="email" class="form-control form-account" id="exampleInputEmail1">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password *</label>
                            <input type="password" class="form-control form-account" id="exampleInputPassword1">
                        </div>
                        <div class="form-check">
                            <button type="submit" class="btn-login btn-register hover-white">Register</button>
                        </div>
                    </form>
                    <span class="divider"></span>
                    <a href="" class="btn link-button create-account hover-black">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
