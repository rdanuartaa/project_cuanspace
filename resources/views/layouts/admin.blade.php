<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Space</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('vendors_template/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors_template/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('vendors_template/css/vertical-layout-light/style.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('vendors_template/images/favicon.png') }}" />
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('vendors_template/images/favicon.png') }}" />

    <!-- Tambahan CSS untuk thumbnail agar tidak bulat -->
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
            <a class="navbar-brand brand-logo" href="index.html">
                <img src="{{ asset('images/logo.svg') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="index.html">
                <img src="{{ asset('images/logo-mini.svg') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Hallo, <span class="text-black fw-bold">{{ auth()->guard('admin')->user()->name }}</p></span></h1>
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
                    <img class="img-xs rounded-circle" src="{{ asset('vendors_template/images/faces/face8.jpg') }}"
                        alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="{{ asset('vendors_template/images/faces/face8.jpg') }}"
                            alt="Profile image">
                        @if (auth()->guard('admin')->check())
                            <p class="mb-1 mt-3 font-weight-semibold">{{ auth()->guard('admin')->user()->name }}</p>
                            <p class="fw-light text-muted mb-0">{{ auth()->guard('admin')->user()->email }}</p>
                        @else
                            <p class="text-danger">Belum login sebagai admin</p>
                        @endif
                    </div>
                    <a class="dropdown-item"><i
                            class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile <span
                            class="badge badge-pill badge-danger"></span></a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
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
<!-- partial -->
<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_settings-panel.html -->
    <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
            <i class="settings-close ti-close"></i>
            <p class="settings-heading">SIDEBAR SKINS</p>
            <div class="sidebar-bg-options selected" id="sidebar-light-theme">
                <div class="img-ss rounded-circle bg-light border me-3"></div>Light
            </div>
            <div class="sidebar-bg-options" id="sidebar-dark-theme">
                <div class="img-ss rounded-circle bg-dark border me-3"></div>Dark
            </div>
            <p class="settings-heading mt-2">HEADER SKINS</p>
            <div class="color-tiles mx-0 px-4">
                <div class="tiles success"></div>
                <div class="tiles warning"></div>
                <div class="tiles danger"></div>
                <div class="tiles info"></div>
                <div class="tiles dark"></div>
                <div class="tiles default"></div>
            </div>
        </div>
    </div>

    <!-- partial -->
    <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
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
                <a class="nav-link" href="#">
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
                <a class="nav-link" href="#">
                    <i class="menu-icon mdi mdi-account-multiple-outline"></i>
                    <span class="menu-title">Teams</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="menu-icon mdi mdi-settings"></i>
                    <span class="menu-title">Pengaturan Platform</span>
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
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->

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
