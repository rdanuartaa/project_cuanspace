@extends('layouts.main')

@section('content')
<div class="container agency-container v3">
    <div class="about-shop-ver1">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 shop-content">
                <h3 class="brand-title">TENTANG KAMI</h3>
                <h2 class="agency-title-noline ver3">{{ $about->judul ?? 'Tempat Kreator Digital Tampil, Berkembang, dan Cuan' }}</h2>
                <p>{{ $about->deskripsi ?? 'CUAN SPACE adalah platform yang dirancang untuk para kreator digital muda Indonesia. Di sini, kamu bisa mempromosikan karya digitalmu—mulai dari desain, template, font, hingga stiker—tanpa ribet. Kami bantu kamu dikenal lebih luas dan menghasilkan dari passion yang kamu bangun.' }}</p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div class="cosre-bg text-center">
                    @if($about->thumbnail)
                        <img src="{{ asset('storage/' . $about->thumbnail) }}" alt="Thumbnail About" style="width: 645px; height: 459px; object-fit: contain; border-radius: 4px; margin-right: 10px;">
                    @else
                        <img src="{{ asset('img/about/cosre-logo.jpg') }}" alt="Default Thumbnail" class="img-fixed-crop">
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="about-shop-ver2">
        <div class="shop-content ver2">
            <h3 class="shop-title">Visi</h3>
            <p>{{ $about->visi ?? 'Visi belum tersedia' }}</p>
        </div>
        <div class="shop-content ver2">
            <h3 class="shop-title">Misi</h3>
            <p>{{ $about->misi ?? 'Misi belum tersedia' }}</p>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@endsection