<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Http\Controllers\Main\SellerRegisterController; 
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SellerMiddleware;

// Halaman utama (tanpa login)
Route::get('/', function () {
    return view('main.home');
})->name('home');

// Halaman home untuk user biasa setelah login
Route::get('/home', function () {
    return view('main.home');
})->middleware(['auth', 'verified'])->name('main.home');

// Profil untuk pengguna biasa
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Halaman register sebagai seller
    Route::get('/seller-register', [SellerRegisterController::class, 'showForm'])->name('seller.register');
    Route::post('/seller-register', [SellerRegisterController::class, 'register'])->name('seller.register.submit');
});

// Rute untuk admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Halaman login untuk admin (tanpa middleware)
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    
    // Logout admin (tanpa middleware)
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Dashboard admin dan rute-rute yang membutuhkan autentikasi admin
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Halaman untuk mengelola seller yang mendaftar
        Route::get('sellers', [AdminSellerController::class, 'index'])->name('sellers.index');
        Route::post('sellers/{id}/verify', [AdminSellerController::class, 'verify'])->name('sellers.verify');
    });
});

// Rute untuk seller
Route::prefix('seller')->name('seller.')->middleware(['auth'])->group(function () {
    // Dashboard untuk seller yang sudah aktif
    Route::get('dashboard', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
});

// Menggunakan Laravel Breeze untuk login dan autentikasi user biasa
require __DIR__.'/auth.php';