@extends('layouts.main')

@section('content')

    <body>
        <div class="wrappage">
            <div class="container agency-container v3 ">
                <div class="about-shop-ver1">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 shop-content">
                            <h3 class="brand-title">TENTANG KAMI</h3>
                            <h2 class="agency-title-noline ver3">
                                {{ $about->judul ?? 'Tempat Kreator Digital Tampil, Berkembang, dan Cuan' }}</h2>
                            <p>{{ $about->deskripsi ?? 'CUAN SPACE adalah platform yang dirancang untuk para kreator digital muda Indonesia. Di sini, kamu bisa mempromosikan karya digitalmu—mulai dari desain, template, font, hingga stiker—tanpa ribet. Kami bantu kamu dikenal lebih luas dan menghasilkan dari passion yang kamu bangun.' }}
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="cosre-bg text-center">
                                @if ($about->thumbnail)
                                    <img src="{{ asset('storage/' . $about->thumbnail) }}" alt="Thumbnail About"
                                        class="img-responsive"
                                        style="width: 646px; height: 459px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <img src="{{ asset('img/about/cosre-logo.jpg') }}" alt="Default Thumbnail"
                                        class="img-fluid rounded"
                                        style="width: 645px; height: 459px; object-fit: cover; border-radius: 4px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-shop-ver2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="shop-content" style="font-size: 16px; line-height: 1.8; padding: 15px;">
                                <h3 class="shop-title">Visi</h3>
                                <p style="text-align: justify;">
                                    {{ $about->visi ?? 'Visi belum tersedia' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="shop-content" style="font-size: 16px; line-height: 1.8; padding: 15px;">
                                <h3 class="shop-title">Misi</h3>
                                <p style="text-align: justify;">
                                    {{ $about->misi ?? '1. Menyediakan ruang digital yang ramah dan mudah digunakan bagi kreator muda. 2. Mendukung pertumbuhan ekonomi kreatif dengan sistem yang adil dan transparan. 3. Menghubungkan kreator dan pembeli dengan pengalaman jual beli yang aman. 4. Mendorong kreativitas Gen Z dengan fitur-fitur relevan. 5. Menjadi komunitas yang saling dukung dan berkembang.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
@endsection
