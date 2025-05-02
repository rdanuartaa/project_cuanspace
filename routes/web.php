<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Main\SellerRegisterController; // Import controller SellerRegisterController

Route::get('/', function () {
    return view('main.home'); // Halaman depan yang bisa diakses tanpa autentikasi
});

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

// Rute login admin dan dashboard admin
Route::prefix('admin')->name('admin.')->group(function () {
    // Halaman login untuk admin
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');

    // Dashboard admin, hanya bisa diakses oleh admin yang terautentikasi
    Route::middleware('auth:admin')->get('dashboard', function () {
        return view('admin.dashboard'); // Dashboard admin
    })->name('dashboard');

    // Halaman untuk mengelola seller yang mendaftar
    Route::middleware('auth:admin')->get('sellers', [SellerController::class, 'index'])->name('sellers.index');
    Route::middleware('auth:admin')->post('sellers/{id}/verify', [SellerController::class, 'verify'])->name('sellers.verify');

    // Logout admin
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard untuk seller yang sudah aktif
    Route::get('dashboard', function () {
        return view('seller.dashboard');  // Halaman dashboard seller
    })->name('dashboard');
});

// Menggunakan Laravel Breeze untuk login dan autentikasi user biasa
require __DIR__.'/auth.php';
