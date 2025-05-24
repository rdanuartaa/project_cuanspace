@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="faq-title text-center">Pertanyaan Umum Seputar Penggunaan Platform Cuan Space<br>
            oleh Kreator Digital dan Pembeli</h2>
        <div class="faq js-faq">
            <div class="faq-content">
                @forelse ($faqs as $faq)
                    <div class="faq-content mb-4">
                        <a href="#" onClick="return false;" class="faq-quest">{{ $faq->question }}</a>
                        <span class="plus js-plus-icon"></span>
                        <div class="faq-answer">
                            <p>{!! nl2br(e($faq->answer)) !!}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">Tidak ada pertanyaan tersedia.</p>
                @endforelse

                <div class="pagination mt-6">
                    {{ $faqs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
