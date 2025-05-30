<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Space</title>
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/js/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/logocuanspace.png') }}" />
    <link rel="shortcut icon" href="{{ asset('img/logocuanspace.png') }}" />
    <style>
        /* Pastikan ini override semua kemungkinan class pembulatan */
        .thumbnail-img {
            width: 150px !important;
            height: auto !important;
            object-fit: contain !important;
            border-radius: 4px !important;
            /* bukan 50% */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
            display: block !important;
            margin: auto !important;
        }

        /* Override class auto seperti rounded-circle, img-xs, img-md */
        .thumbnail-img.rounded-circle,
        .thumbnail-img.img-xs,
        .thumbnail-img.img-md {
            border-radius: 4px !important;
        }
    </style>


</head>
<!-- partial:partials/_navbar.html -->
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard.index') }}">
                <img src="{{ asset('images/adminspace.svg') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard.index') }}">
                <img src="{{ asset('img/logocuanspace.png') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Hallo, <span
                        class="text-black fw-bold">{{ auth()->guard('admin')->user()->name }}</p></span></h1>
                <h3 class="welcome-sub-text">Ayo kembangkan platformmu dan raih cuan maksimal!</h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item d-none d-lg-block">
                <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
                    <span class="input-group-addon input-group-prepend border-right">
                        <span class="icon-calendar input-group-text calendar-icon"></span>
                    </span>
                    <input type="text" class="form-control">
                </div>
            </li>
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-account-circle-outline mdi-36px"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <!-- Ganti img dengan ikon -->
                        <i class="mdi mdi-account-circle-outline mdi-36px"></i>

                        @if (auth()->guard('admin')->check())
                            <p class="mb-1 mt-2 font-weight-semibold">{{ auth()->guard('admin')->user()->name }}</p>
                            <p class="fw-light text-muted mb-0">{{ auth()->guard('admin')->user()->email }}</p>
                        @else
                            <p class="fw-light text-danger">Belum login sebagai admin</p>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="dropdown-item w-100 text-start">
                            <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
            data-bs-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
<div class="container-fluid page-body-wrapper">
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                    <i class="mdi mdi-grid-large menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">
                    <i class="menu-icon mdi mdi-account-check"></i>
                    <span class="menu-title">Kelola Pengguna</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.sellers.index') }}">
                    <i class="menu-icon mdi mdi-shopping"></i>
                    <span class="menu-title">Kelola Seller</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('admin.produk.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.produk.index') }}">
                    <i class="menu-icon mdi mdi-package-variant-closed"></i>
                    <span class="menu-title">Kelola Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.kategori.index') }}">
                    <i class="menu-icon mdi mdi-puzzle"></i>
                    <span class="menu-title">Kelola Kategori</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.transaksi.index') }}">
                    <i class="menu-icon mdi mdi-repeat"></i>
                    <span class="menu-title">Kelola Transaksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.saldo.index') }}">
                    <i class="menu-icon mdi mdi-credit-card"></i>
                    <span class="menu-title">Kelola Penarikan Saldo</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.ulasan.index') }}">
                    <i class="menu-icon mdi mdi-star"></i>
                    <span class="menu-title">Kelola Ulasan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.notifications.index') }}">
                    <i class="menu-icon mdi mdi-voice"></i>
                    <span class="menu-title">Pemberitahuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.faq.index') }}">
                    <i class="menu-icon mdi mdi-help"></i>
                    <span class="menu-title">FAQ</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.about.index') }}"
                    class="nav-link {{ request()->is('admin/about*') ? 'active' : '' }}">
                    <i class="menu-icon mdi mdi-information"></i>
                    <span class="menu-title">About</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.teams.index') }}">
                    <i class="menu-icon mdi mdi-account-multiple-outline"></i>
                    <span class="menu-title">Teams</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    <div class="home-tab">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- main-panel ends -->
</div>
<!-- plugins:js -->
<script src="{{ asset('/vendors_template/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ asset('/vendors_template/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('/vendors_template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/vendors_template/vendors/progressbar.js/progressbar.min.js') }}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ asset('/vendors_template/js/off-canvas.js') }}"></script>
<script src="{{ asset('/vendors_template/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('/vendors_template/js/template.js') }}"></script>
<script src="{{ asset('/vendors_template/js/settings.js') }}"></script>
<script src="{{ asset('/vendors_template/js/todolist.js') }}j"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ asset('/vendors_template/js/jquery.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('/vendors_template/js/dashboard.js') }}"></script>
<script src="{{ asset('/vendors_template/js/Chart.roundedBarCharts.js') }}"></script>
<!-- End custom js for this page-->
</body>

</html>
