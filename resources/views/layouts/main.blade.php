<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Cuan Space</title>
    <link rel="stylesheet" href="css/slick.css">
    <link rel="stylesheet" href="css/slick-theme.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/png">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-slider.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">SEARCH HERE</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <form method="get" class="searchform" action="/search" role="search">
                            <input type="hidden" name="type" value="product">
                            <input type="text" name="q" class="form-control control-search">
                            <span class="input-group-btn">
                                <button class="btn btn-default button_search" type="button">
                                    <i data-toggle="dropdown" class="fa fa-search"></i>
                                </button>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END  Modal content-->
    <header id="header" class="header-v1">
        <div class="sticky-header text-center hidden-xs hidden-sm">
            <div class="text">
                <a class="show-login js-showlogin" href="{{ route('seller.register') }}">Seller Space</a> yuk gabung dan jual produk digitalmu
            </div>
        </div>
        <div class="topbar">
            <div class="container container-40">
                <div class="topbar-left">
                    <div class="topbar-option">
                        <div class="topbar-account">
                            @auth
                                <li class="level1 active dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="icon-user f-15"></i>
                                    </a>
                                    <span class="plus js-plus-icon"></span>
                                    <ul class="dropdown-menu menu-level-1">
                                        <li class="level2">
                                            <a href="{{ route('profile.edit') }}" title="Edit Profil">Edit Profil</a>
                                        </li>
                                        <li class="level2">
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="lost-password">
                                                Keluar
                                            </a>
                                        </li>
                                    </ul>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            @endauth
                            @guest
                                <li class="level1 active dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                        <div class="topbar-wishlist">
                            <a href="#">
                                <i class="icon-heart f-15"></i>
                                <span class="count wishlist-count">0</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="logo hidden-xs hidden-sm">
                    <a href="{{ url('/') }}" title="home-logo">
                        <img src="{{ asset('img/logocuanspace.png') }}" alt="logo" class="img-reponsive">
                    </a>
                </div>
                <div class="topbar-right">
                    <div class="topbar-option">
                        <div class="topbar-search">
                            <div class="search-popup dropdown" data-toggle="modal" data-target="#myModal">
                                <a href="#"><i class="icon-magnifier f-15"></i></a>
                            </div>
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
                                <img src="{{ asset('img/cosre.png') }}" alt="logo" class="img-reponsive">
                            </a>
                        </div>
                        <button type="button" class="navbar-toggle icon-mobile" data-toggle="collapse" data-target="#myNavbar">
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
                                                <li class="level2"><a href="{{ route('login') }}" title="Login">Login</a></li>
                                                <li class="level2"><a href="{{ route('register') }}" title="Register">Register</a></li>
                                            </ul>
                                        </li>
                                    @endguest
                                    @auth
                                        <li class="level1 active dropdown">
                                            <a href="#">Gabung</a>
                                            <span class="plus js-plus-icon"></span>
                                            <ul class="dropdown-menu menu-level-1">
                                                <li class="level2"><a href="{{ route('seller.register') }}" title="Seller Register">Gabung sebagai Seller</a></li>
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

    @yield('content')

    <footer>
        <div class="container container-42">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="menu-footer">
                        <ul>
                            <li><a href="#">Shipping</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                    <div class="newletter-form">
                        <h3 class="footer-title text-center">Newsletter</h3>
                        <form action="#">
                            <input type="text" name="s" placeholder="Email Adress..." class="form-control">
                            <button type="submit" class="btn btn-submit">
                                <i class="fa fa-angle-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                    <div class="social">
                        <a href="#" title="twitter">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" title="facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" title="google plus">
                            <i class="fa fa-google-plus"></i>
                        </a>
                        <a href="#" title="Pinterest">
                            <i class="fa fa-pinterest-p"></i>
                        </a>
                        <a href="#" title="rss">
                            <i class="fa fa-rss"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <a href="#" class="scroll_top">SCROLL TO TOP<span></span></a>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <!-- Script untuk memastikan logout berfungsi dengan benar -->
    <script>
        $(document).ready(function() {
            // Tambahkan konfirmasi logout jika diinginkan
            $('.logout-btn').click(function(e) {
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    // Lanjutkan dengan logout
                    return true;
                } else {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>
</html>
