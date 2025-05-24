@extends('layouts.main')
@section('content')
<div class="container">
    <div class="our-teams">
        <div class="text-center">
            <h2 class="us-title text-center">
                Team Pengembang Platform Cuan Space
            </h2>
            <p class="us-desc">Di balik CUAN SPACE, ada tim muda penuh ide, semangat, dan aksi nyata!
                <br>Kami bukan cuma bikin platform, tapi membangun ruang cuan digital untuk generasi masa kini.</p>
        </div>
        <div class="owl-carousel owl-theme js-owl-team">
            @foreach($teams as $team)
                <div class="team-item">
                    <div class="team-img">
                        <img src="{{ asset('storage/' . $team->image) }}" alt="{{ $team->name }}"class="img-responsive"
                                        style="width: 340px; height: 400px; object-fit: cover; border-radius: 4px;">
                    </div>
                    <div class="team-info">
                        <h3 class="team-name"><a href="#">{{ $team->name }}</a></h3>
                        <p class="team-career">{{ strtoupper($team->role) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
