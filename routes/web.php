<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TeamsController;
// ---------------- HALAMAN DEPAN / USER ----------------

// Halaman utama (tanpa login)
Route::get('/', fn () => view('main.home'))->name('home');

// Halaman setelah login user biasa
Route::get('/home', fn () => view('main.home'))
    ->middleware(['auth', 'verified'])
    ->name('main.home');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/teams', [TeamsController::class, 'index'])->name('teams');

// Profil user biasa & daftar jadi seller
Route::middleware('auth')->group(function () {
    // Pengelolaan profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Form & submit daftar jadi seller
    Route::get('/seller-register', [SellerController::class, 'showRegistrationForm'])->name('seller.register');
    Route::post('/seller-register', [SellerController::class, 'register'])->name('seller.register.submit');
});

// ---------------- ADMIN ----------------
Route::prefix('admin')->name('admin.')->group(function () {
    // Login & logout admin
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Dashboard & manajemen (hanya untuk admin)
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Kelola seller
        Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
        Route::post('sellers/{id}/verify', [SellerController::class, 'verify'])->name('sellers.verify');
        Route::post('sellers/{id}/deactivate', [SellerController::class, 'deactivate'])->name('sellers.deactivate');
        Route::post('sellers/{id}/set-pending', [SellerController::class, 'setPending'])->name('sellers.setPending');

        // Kelola kategori
        Route::resource('kategori', KategoriController::class)->except(['show']);

        // Kelola pengguna
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// ---------------- SELLER ----------------
Route::middleware(['auth', \App\Http\Middleware\SellerMiddleware::class])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        // Dashboard seller
        Route::get('dashboard', [SellerController::class, 'dashboard'])->name('dashboard');

        // Manajemen produk
        Route::get('produk/dashboard', [ProductController::class, 'dashboard'])->name('produk.dashboard');
        Route::resource('produk', ProductController::class)->except(['show']);
        Route::get('produk', [ProductController::class, 'index'])->name('produk');

    });

require __DIR__.'/auth.php';
