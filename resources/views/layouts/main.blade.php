<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Cuan Space</title>
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/logocuanspace.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-slider.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <!-- Styles khusus dari child view -->
    @yield('styles')
</head>

<body>
    <header id="header" class="header-v1">
        <div class="sticky-header text-center hidden-xs hidden-sm">
            <div class="text">
                <a class="show-login js-showlogin" href="{{ route('seller.register') }}">Seller Space</a> yuk gabung dan
                jual produk digitalmu
            </div>
        </div>
        <div class="topbar">
            <div class="container container-40">
                <div class="topbar-left">
                    <div class="topbar-option">
                        <div class="topbar-account">
                            @auth
                                <li class="level1 active dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-user f-15"></i>
                                    </a>
                                    <span class="plus js-plus-icon"></span>
                                    <ul class="dropdown-menu menu-level-1">
                                        <li class="level2">
                                            <a href="{{ route('profile.edit') }}" title="Edit Profil">Edit Profil</a>
                                        </li>
                                        <li class="level2">
                                            <a href="{{ route('main.order.history') }}" title="Order History">Order
                                                History</a>
                                        </li>
                                        <li class="level2">
                                            <a href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                class="lost-password">
                                                Keluar
                                            </a>
                                        </li>
                                    </ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            @endauth
                            @guest
                                <li class="level1 active dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="icon-user f-15"></i>
                                    </a>
                                    <span class="plus js-plus-icon"></span>
                                    <ul class="dropdown-menu menu-level-1">
                                        <li class="level2">
                                            <a href="{{ route('login') }}" title="Login">Login untuk mengakses</a>
                                        </li>
                                    </ul>
                                </li>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="logo hidden-xs hidden-sm">
                    <a href="{{ url('/') }}" title="home-logo">
                        <img src="{{ asset('img/cuanspace.png') }}" alt="logo" class="img-reponsive">
                    </a>
                </div>
                <div class="topbar-right">
                    <div class="topbar-option">
                        <div class="topbar-currency dropdown">
                            <span>IDR</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-top">
            <div class="container container-40">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="logo-mobile hidden-lg hidden-md">
                            <a href="{{ url('/') }}" title="home-logo">
                                <img src="{{ asset('img/logocuanspace.png') }}" alt="logo" class="img-reponsive">
                            </a>
                        </div>
                        <button type="button" class="navbar-toggle icon-mobile" data-toggle="collapse"
                            data-target="#myNavbar">
                            <span class="icon-menu"></span>
                        </button>
                        <nav class="navbar main-menu">
                            <div class="collapse navbar-collapse" id="myNavbar">
                                <ul class="nav navbar-nav js-menubar">
                                    <li class="level1 dropdown hassub">
                                        <a href="{{ route('faq') }}">FAQ</a>
                                    </li>
                                    <li class="level1 active dropdown">
                                        <a href="{{ route('about') }}">About</a>
                                    </li>
                                    <li class="level1 active dropdown">
                                        <a href="{{ url('/') }}">Home</a>
                                    </li>
                                    <li class="level1 active dropdown">
                                        <a href="{{ route('teams') }}">Teams</a>
                                    </li>
                                    @guest
                                        <li class="level1 active dropdown">
                                            <a href="#">Masuk</a>
                                            <span class="plus js-plus-icon"></span>
                                            <ul class="dropdown-menu menu-level-1">
                                                <li class="level2"><a href="{{ route('login') }}"
                                                        title="Login">Login</a></li>
                                                <li class="level2"><a href="{{ route('register') }}"
                                                        title="Register">Register</a></li>
                                            </ul>
                                        </li>
                                    @endguest
                                    @auth
                                        <li class="level1 active dropdown">
                                            <a href="#">Gabung</a>
                                            <span class="plus js-plus-icon"></span>
                                            <ul class="dropdown-menu menu-level-1">
                                                <li class="level2"><a href="{{ route('seller.register') }}"
                                                        title="Seller Register">Gabung sebagai Seller</a></li>
                                            </ul>
                                        </li>
                                    @endauth
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="container mt-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Konten halaman -->
    @yield('content')

    <footer>
        <div class="container container-42">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="menu-footer">
                        <ul>
                            <li><a href="{{ route('faq') }}">FAQ</a></li>
                            <li><a href="{{ route('about') }}">Tentang Cuan Space</a></li>
                            <li><a href="{{ route('teams') }}">Tim Pengembang</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="newletter-form">
                        <h3 class="footer-title text-center">Dikembangkan untuk Berkembang</h3>
                        <div class="text-center mt-4">
                            <hr style="max-width: 300px; ">
                            <p class="mt-3 mb-0">&copy; 2025 <strong>Cuan Space</strong>. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <div class="menu-footer text-end" style="text-align: right;">
                        <ul>
                            <li><a href="https://www.instagram.com/namoyteam">Namoy Team</a></li>
                            <li><a href="https://laravel.com/">Laravel Framework</a></li>
                            <li><a href="https://docs.midtrans.com/">Midtrans</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <a href="#" class="scroll_top">SCROLL TO TOP<span></span></a>

    <!-- Script JS umum -->
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Script logout -->
    <script>
        $(document).ready(function() {
            $('.logout-btn').click(function(e) {
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    return true;
                } else {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

    <!-- Scripts khusus dari child view -->
    @yield('scripts')
</body>

</html>
