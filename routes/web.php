<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Main\SellerRegisterController;
use App\Http\Controllers\Seller\ProductController; // Added this import

// ---------------- HALAMAN DEPAN / USER ----------------

// Halaman depan (guest)
use App\ttp\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SellerMiddleware;
use App\Http\Controllers\Admin\UserController;

// ---------------- HALAMAN DEPAN / USER ----------------

// Halaman utama (tanpa login)
Route::get('/', function () {
    return view('main.home');
})->name('home');

// Halaman home setelah login user biasa
Route::get('/home', function () {
    return view('main.home');
})->middleware(['auth', 'verified'])->name('main.home');

// Profil user biasa dan register seller
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/seller-register', [SellerRegisterController::class, 'showForm'])->name('seller.register');
    Route::post('/seller-register', [SellerRegisterController::class, 'register'])->name('seller.register.submit');
});

// ---------------- ADMIN ----------------

Route::prefix('admin')->name('admin.')->group(function () {
    // Login admin
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');

    // Logout admin
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Semua halaman admin setelah login
    Route::middleware('auth:admin')->group(function () {
        // Dashboard admin
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Kelola Seller
        Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
        Route::post('sellers/{id}/verify', [SellerController::class, 'verify'])->name('sellers.verify');
        Route::post('sellers/{id}/deactivate', [SellerController::class, 'deactivate'])->name('sellers.deactivate');
        Route::post('sellers/{id}/set-pending', [SellerController::class, 'setPending'])->name('sellers.setPending');

        // Kelola Kategori
        Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // Kelola Pengguna
        Route::get('users', [UserController::class, 'index'])->name('user.index');

    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    });
});

// ---------------- SELLER ----------------


Route::middleware(['auth'])->prefix('seller')->group(function () {
    // Dashboard
    Route::get('dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
    
    // Produk management - WITHOUT using name('seller.') in the group
    Route::get('produk', [ProductController::class, 'index'])->name('seller.produk');
    Route::get('produk/create', [ProductController::class, 'create'])->name('seller.produk.create');
    Route::post('produk', [ProductController::class, 'store'])->name('seller.produk.store');
    Route::get('produk/{id}/edit', [ProductController::class, 'edit'])->name('seller.produk.edit');
    Route::put('produk/{id}', [ProductController::class, 'update'])->name('seller.produk.update');
    Route::delete('produk/{id}', [ProductController::class, 'destroy'])->name('seller.produk.destroy');
});

// ---------------- AUTENTIKASI USER BIASA ----------------

require __DIR__.'/auth.php';

Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Rute admin di dalam grup seller (dipertahankan sesuai permintaan)
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('sellers', [AdminSellerController::class, 'index'])->name('sellers.index');
        Route::post('sellers/{id}/verify', [AdminSellerController::class, 'verify'])->name('sellers.verify');
    });
});

require __DIR__.'/auth.php';
