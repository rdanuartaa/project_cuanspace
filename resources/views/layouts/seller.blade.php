<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Seller Space</title>
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
            <a class="navbar-brand brand-logo" href="#">
                <img src="{{ asset('images/logo.svg') }}" alt="svg" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="#">
                <img src="{{ asset('images/logo-mini.svg') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Hallo, <span class="text-black fw-bold">{{ $seller->brand_name }}</span></h1>
                <h3 class="welcome-sub-text">Ayo jual produk digitalmu biar makin banyak cuan!</h3>
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
                    <img class="img-xs rounded-circle" src="{{ asset('storage/seller/profile/'.$seller->profile_image) }}"
                        alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <div class="dropdown-header text-center">
                        <img class="img-md rounded-circle" src="{{ asset('storage/seller/profile/'.$seller->profile_image) }}"
                            alt="Profile image"style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                        <p class="mb-1 mt-3 font-weight-semibold">{{ $seller->brand_name }}</p>
                        <p class="fw-light text-muted mb-0">{{ $seller->contact_email }}</p>
                    </div>
                    <a class="dropdown-item" href="{{ route('seller.pengaturan.index') }}">
                        <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> My Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </li>
    </div>
</nav>
<!-- partial -->
<div class="container-fluid page-body-wrapper">

    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.dashboard.index') }}">
                    <i class="mdi mdi-grid-large menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.produk.index') }}">
                    <i class="menu-icon mdi mdi-package-variant-closed"></i>
                    <span class="menu-title">Produk Saya</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.penjualan.index') }}">
                    <i class="menu-icon mdi mdi-shopping"></i>
                    <span class="menu-title">Penjualan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.saldo.index') }}">
                    <i class="menu-icon mdi mdi-package-variant-closed"></i>
                    <span class="menu-title">Saldo Saya</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.penghasilan.index') }}">
                    <i class="menu-icon mdi mdi-cash-multiple"></i>
                    <span class="menu-title">Penghasilan Saya</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.ulasan.index') }}">
                    <i class="menu-icon mdi mdi-star"></i>
                    <span class="menu-title">Ulasan Produk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('seller.pengaturan.index') }}">
                    <i class="menu-icon mdi mdi-settings"></i>
                    <span class="menu-title">Pengaturan Toko</span>
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
<script src="{{ asset('/vendors_template/js/todolist.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ asset('/vendors_template/js/jquery.cookie.js') }}" type="text/javascript"></script>
<script src="{{ asset('/vendors_template/js/dashboard.js') }}"></script>
<script src="{{ asset('/vendors_template/js/Chart.roundedBarCharts.js') }}"></script>
<!-- End custom js for this page-->
@stack('scripts')
</body>

</html>
