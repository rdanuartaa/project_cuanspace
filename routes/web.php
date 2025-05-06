<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Main\SellerRegisterController;

// ---------------- HALAMAN DEPAN / USER ----------------

// Halaman depan (guest)
Route::get('/', function () {
    return view('main.home');
});

// Halaman home setelah login user biasa
Route::get('/home', function () {
    return view('main.home');
})->middleware(['auth', 'verified'])->name('main.home');

// Profil user biasa
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Register sebagai seller
    Route::get('/seller-register', [SellerRegisterController::class, 'showForm'])->name('seller.register');
    Route::post('/seller-register', [SellerRegisterController::class, 'register'])->name('seller.register.submit');
});


// ---------------- ADMIN ----------------

Route::prefix('admin')->name('admin.')->group(function () {
    // Login admin
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Semua halaman admin setelah login
    Route::middleware('auth:admin')->group(function () {
        // Dashboard admin
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Kelola Seller
        Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
        Route::post('sellers/{id}/verify', [SellerController::class, 'verify'])->name('sellers.verify');

        // ✅ Kelola Kategori
        Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
        Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::get('kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
        Route::put('kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy'); // ✅ ini yang sebelumnya error
    });
});


// ---------------- SELLER ----------------

Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('dashboard', function () {
        return view('seller.dashboard');
    })->name('dashboard');
});


// ---------------- AUTENTIKASI USER BIASA ----------------

require __DIR__.'/auth.php';
