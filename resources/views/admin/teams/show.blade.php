@extends('layouts.app')

@section('title', 'Our Teams')

@section('content')
<div class="container">
    <h2>Meet Our Team</h2>
    <div class="row">
        @foreach ($teams as $team)
        <div class="col-md-4">
            <div class="card">
                <img src="{{ asset('storage/' . $team->image) }}" class="card-img-top" alt="Team Image">
                <div class="card-body">
                    <h5 class="card-title">{{ $team->name }}</h5>
                    <p class="card-text">{{ $team->role }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
