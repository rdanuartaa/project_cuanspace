@extends('layouts.main')
@section('content')
    <div class="page-heading">
        <div class="banner-heading">
            <img src="img/headerbg_2.jpg" alt="" class="img-reponsive">
            <div class="heading-content text-center">
                <div class="container container-42">
                    <h1 class="page-title white">Shop</h1>
                    <ul class="breadcrumb white">
                        <li><a href="">home</a></li>
                        <li><a href="">Shop All Products</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nav nav-tabs nav-justified nav-filter white">
            <ul class="owl-carousel owl-theme js-owl-category">
                <li class="{{ !request('kategori') || request('kategori') == 'all' ? 'active' : '' }}">
                    <a href="{{ route('home', ['kategori' => 'all']) }}">All</a>
                </li>
                @foreach ($kategoris as $kategori)
                    <li class="{{ request('kategori') == $kategori->id ? 'active' : '' }}">
                        <a href="{{ route('home', ['kategori' => $kategori->id]) }}">{{ $kategori->nama_kategori }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="wrap-filter">
        <div class="wrap-filter-box wrap-filter-number">
            <ul class="pagination">
                <li class="active"><a href="">{{ $products->currentPage() }}</a></li>
                @if ($products->hasMorePages())
                    <li><a href="{{ $products->nextPageUrl() }}">{{ $products->currentPage() + 1 }}</a></li>
                @endif
            </ul>
            <span class="total-count">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of
                {{ $products->total() }} products</span>
        </div>
        <div class="wrap-filter-box text-center view-mode">
            <a class="col" href="#" onClick="return false;"><span class="icon-grid-img"></span></a>
        </div>
        <div class="wrap-filter-box text-center js-filter"><a href="#" class="filter-title"><i
                    class="icon-equalizer"></i></a>
            <form action="#" method="get" class="form-filter-product js-filter-open">
                <span class="close-left js-close"><i class="icon-close f-20"></i></span>
                <div class="product-filter-wrapper">
                    <div class="product-filter-inner text-left">
                        <div class="product-filter">
                            <div class="form-group">
                                <span class="title-filter">Category</span>
                                <select name="kategori" class="form-control" onchange="this.form.submit()">
                                    <option value="all"
                                        {{ !request('kategori') || request('kategori') == 'all' ? 'selected' : '' }}>All
                                        Categories</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="product-filter">
                            <div class="form-group">
                                <span class="title-filter">Price</span>
                                <div class="filter-content">
                                    <div class="price-range-holder">
                                        <input type="text" class="price-slider" value="">
                                    </div>
                                    <span class="min-max">
                                        Price: Rp 10k — 1000k
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="product-filter-button-group clearfix">
                        <div class="product-filter-button">
                            <a href="" class="btn-submit">Filter</a>
                        </div>
                        <div class="product-filter-button">
                            <a href="{{ route('home') }}" class="btn-submit">Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="wrap-filter-box text-center view-mode">
            <a class="list" href="#" onClick="return false;"><span class="icon-list-img"></span></a>
        </div>
        <div class="wrap-filter-box wrap-filter-sorting">
            <button class="dropdown-toggle" type="button" data-toggle="dropdown" id="menu2">Sort by newness</button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
                <li><a href="#" title="">Sort by newness</a></li>
                <li><a href="#" title="">Best Selling</a></li>
                <li><a href="#" title="">Price Low to High</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="product-standard product-grid">
        <div class="container container-42">
            <div class="tab-content">
                <div id="all" class="tab-pane fade in active">
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                                <div class="product-images">
                                    <a href="{{ route('public.produk.show', $product->id) }}" class="hover-images effect">
                                        @if ($product->thumbnail)
                                            <img src="{{ asset('storage/thumbnails/' . $product->thumbnail) }}"
                                                alt="{{ $product->name }}" class="img-reponsive"
                                                style="width: 300px; height: 400px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <img src="{{ asset('img/products/placeholder.jpg') }}"
                                                alt="{{ $product->name }}" class="img-reponsive"
                                                style="width: 443px; height: 400px; object-fit: cover; border-radius: 4px;">
                                        @endif
                                    </a>
                                    @auth
                                        <a href="{{ route('public.produk.show', $product->id) }}" class="btn-quickview">VIEW
                                            DETAIL</a>
                                    @else
                                        <a href="javascript:void(0)" onclick="showLoginPrompt()" class="btn-quickview">VIEW
                                            DETAIL</a>
                                    @endauth
                                </div>
                                <div class="product-info-ver2">
                                    <h3 class="product-title">
                                        <a href="#">{{ \Illuminate\Support\Str::limit($product->name, 25) }}</a>
                                    </h3>
                                    <div class="product-after-switch">
                                        <div class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                        <div class="product-after-button">
                                            @auth
                                                <a href="{{ route('main.processCheckout', $product->id) }}"
                                                    class="addcart">Checkout Now</a>
                                            @else
                                                <a href="javascript:void(0)" onclick="showLoginPrompt()"
                                                    class="addcart">Checkout Now</a>
                                            @endauth
                                        </div>
                                    </div>
                                    <div class="rating-star text-warning d-flex align-items-center my-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $product->full_stars)
                                            ★
                                        @elseif ($product->has_half_star && $i == $product->full_stars + 1)
                                            ½★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                    <small class="text-muted ml-2">
                                        ({{ $product->reviews->count() }} ulasan)
                                    </small>
                                </div>
                                    <p class="product-desc">
                                        {{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                                    <div class="product-price">
                                        <small class="text-muted">by
                                            {{ $product->seller->brand_name ?? 'Unknown Seller' }}</small>
                                    </div>
                                    <div class="product-category mt-2">
                                        <span
                                            class="badge badge-secondary badge-lg">{{ $product->kategori->nama_kategori ?? 'Uncategorized' }}</span>
                                    </div>
                                    <div class="button-group">
                                        @auth
                                            <a href="{{ route('main.processCheckout', $product->id) }}"
                                                class="button add-to-cart">Checkout Now</a>
                                            <a href="{{ route('public.produk.show', $product->id) }}"
                                                class="button add-view">Quick view</a>
                                        @else
                                            <a href="javascript:void(0)" onclick="showLoginPrompt()"
                                                class="button add-to-cart">Checkout Now</a>
                                            <a href="javascript:void(0)" onclick="showLoginPrompt()"
                                                class="button add-view">Quick view</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <h3>Tidak ada produk yang tersedia</h3>
                                <p>Belum ada produk yang dipublikasikan untuk kategori ini.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Lihat Semua Produk</a>
                            </div>
                        @endforelse
                    </div>

                    @if ($products->hasPages())
                        <div class="pagination-container pagination-blog button-v text-center">
                            <nav>
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($products->onFirstPage())
                                        <li class="disabled"><span>&laquo;</span></li>
                                    @else
                                        <li><a href="{{ $products->previousPageUrl() }}" rel="prev">&laquo;</a></li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                        @if ($page == $products->currentPage())
                                            <li class="active"><a href="#">{{ $page }}</a></li>
                                        @else
                                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($products->hasMorePages())
                                        <li><a href="{{ $products->nextPageUrl() }}" rel="next">&raquo;</a></li>
                                    @else
                                        <li class="disabled"><span>&raquo;</span></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login Prompt -->
    <div id="loginPromptModal"
        style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div class="modal-content"
            style="background: white; padding: 30px; border-radius: 10px; text-align: center; max-width: 400px; margin: 20px;">
            <h3 style="margin-bottom: 15px; color: #333;">Login Required</h3>
            <p style="margin-bottom: 20px; color: #666;">You must be logged in to view this product. Please log in to
                proceed.</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="window.location.href='{{ route('login') }}'"
                    style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                    Login
                </button>
                <button onclick="closeModal()"
                    style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
                    Cancel
                </button>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Fungsi untuk menampilkan modal login
        function showLoginPrompt() {
            console.log("Modal Show Function Called");
            document.getElementById("loginPromptModal").style.display = "flex";
        }

        // Fungsi untuk menutup modal login
        function closeModal() {
            document.getElementById("loginPromptModal").style.display = "none";
        }

        // Close modal when clicking outside
        document.getElementById('loginPromptModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection

@section('styles')
    <style>
        .product-category {
            margin-top: 10px;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .badge-secondary {
            color: #fff;
            background-color: #6c757d;
        }

        .product-item {
            margin-bottom: 30px;
        }

        .product-title a {
            color: #333;
            text-decoration: none;
        }

        .product-title a:hover {
            color: #007bff;
        }

        .text-muted {
            color: #6c757d !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-md-15 {
                width: 50%;
            }
        }

        @media (max-width: 480px) {
            .col-md-15 {
                width: 100%;
            }
        }
    </style>
@endsection
