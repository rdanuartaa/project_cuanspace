@extends('layouts.main')

@section('content')
<div class="container agency-container v3 py-8">
    <div class="about-shop-ver1 mb-8 text-center">
        <h3 class="brand-title text-lg font-semibold text-gray-600 uppercase tracking-wide">FAQ</h3>
        <h2 class="agency-title-noline ver3 text-3xl md:text-4xl font-bold text-gray-900 mb-4">Pertanyaan yang Sering Diajukan</h2>
        <p class="text-base text-gray-600 max-w-2xl mx-auto">Temukan jawaban atas pertanyaan umum tentang pembelian dan penjualan produk digital di Cuan Space.</p>
        <hr class="border-t border-gray-200 my-6 max-w-2xl mx-auto">
    </div>

    <div class="about-shop-ver2">
        <div class="row justify-content-center">
            <div class="col-12 col-md-11 col-lg-10">
                <div class="faq">
                    @forelse($faqs as $index => $faq)
                        <div class="faq-content mb-6 bg-white p-6 rounded-lg shadow-sm">
                            <div class="faq-quest cursor-pointer hover:text-blue-600 transition-colors">
                                <span class="faq-question-text text-base font-semibold text-gray-800">
                                    {{ $faq->question }}
                                </span>
                            </div>
                            <div class="faq-answer text-sm text-gray-600 mt-3">
                                {!! nl2br(e($faq->answer)) !!}
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-600 py-4 text-sm">Tidak ada FAQ yang tersedia.</div>
                    @endforelse
                    <div class="pagination mt-6">
                        {{ $faqs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .agency-container {
        max-width: 1200px;
        margin: 0 auto;
        padding-left: 15px;
        padding-right: 15px;
    }
    .faq-content {
        transition: all 0.2s ease;
    }
    .faq-content:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .faq-answer {
        display: none;
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        transition: opacity 0.3s ease, max-height 0.3s ease;
        margin-top: 0;
        font-size: 0.85rem;
        line-height: 1.4;
    }
    .faq-content .faq-answer.active {
        display: block;
        max-height: 500px;
        opacity: 1;
        margin-top: 1rem;
    }
    .faq-question-text {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1F2937;
        cursor: pointer;
    }
    .pagination {
        display: flex;
        justify-content: center;
    }
    @media (max-width: 992px) {
        .agency-container {
            max-width: 90%;
        }
        .faq-answer {
            font-size: 0.8rem;
        }
        .faq-question-text {
            font-size: 1rem;
        }
    }
    @media (max-width: 576px) {
        .agency-container {
            max-width: 100%;
            padding-left: 10px;
            padding-right: 10px;
        }
        .faq-question-text {
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questions = document.querySelectorAll('.faq-quest');
        if (questions.length === 0) return;

        questions.forEach(quest => {
            quest.addEventListener('click', function () {
                const answer = this.nextElementSibling;
                if (!answer || !answer.classList.contains('faq-answer')) return;

                const isActive = answer.classList.contains('active');

                document.querySelectorAll('.faq-answer').forEach(otherAnswer => {
                    otherAnswer.classList.remove('active');
                });
                document.querySelectorAll('.faq-quest').forEach(otherQuest => {
                    otherQuest.classList.remove('active');
                });

                if (!isActive) {
                    answer.classList.add('active');
                    this.classList.add('active');
                }
            });
        });
    });
</script>
@endsection
