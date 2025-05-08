<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Main\SellerRegisterController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
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
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// ---------------- SELLER ----------------

// Using the full class path for the middleware instead of the alias
Route::middleware(['auth', \App\Http\Middleware\SellerMiddleware::class])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard
    Route::get('dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

    // Produk management
    Route::get('produk', [ProductController::class, 'index'])->name('produk');
    Route::get('produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('produk', [ProductController::class, 'store'])->name('produk.store');
    Route::get('produk/{id}/edit', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('produk/{id}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('produk/{id}', [ProductController::class, 'destroy'])->name('produk.destroy');

    // Admin routes within seller group (if needed)
    Route::middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::get('admin-dashboard', function () {
            return view('admin.dashboard');
        })->name('admin-dashboard');

        Route::get('admin-sellers', [AdminSellerController::class, 'index'])->name('admin-sellers');
        Route::post('admin-sellers/{id}/verify', [AdminSellerController::class, 'verify'])->name('admin-sellers.verify');
    });
});

// ---------------- AUTENTIKASI USER BIASA ----------------

require __DIR__.'/auth.php';
