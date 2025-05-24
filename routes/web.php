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
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenghasilanController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminSaldoController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\HomeController; // Import HomeController
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PenghasilanExport;
use App\Http\Controllers\Auth\DashboardController;
use App\Models\Transaction;
use Illuminate\Http\Request;

// ---------------- HALAMAN DEPAN / USER ----------------

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('main.home');
Route::get('/faq', [FaqController::class, 'showUserFaqs'])->name('faq');
Route::get('/about', [AboutController::class, 'showUserAbout'])->name('about');
Route::get('/teams', [TeamsController::class, 'showUserTeams'])->name('teams');
Route::get('/produk/{id}', [ProductDetailController::class, 'showUserDetail'])->name('product.detail');

// ---------------- RUTE UNTUK USER ----------------

// Profil user biasa & daftar jadi seller
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/seller-register', [SellerController::class, 'showRegistrationForm'])->name('seller.register');
    Route::post('/seller-register', [SellerController::class, 'register'])->name('seller.register.submit'); // Untuk umum
;
});

// ---------------- ADMIN ----------------
Route::prefix('admin')->name('admin.')->group(function () {
    // Login & logout admin
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminController::class, 'login'])->name('login.submit');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    // Route admin harus auth admin
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('about', AboutController::class);
        Route::resource('teams', TeamsController::class);
        Route::resource('faq', FaqController::class);
        Route::resource('notifications', NotificationsController::class);
        // Kelola seller
        Route::get('sellers', [SellerController::class, 'index'])->name('sellers.index');
        Route::get('sellers/filter', [SellerController::class, 'filter'])->name('sellers.filter');
        Route::post('sellers/{id}/verify', [SellerController::class, 'verify'])->name('sellers.verify');
        Route::post('sellers/{id}/deactivate', [SellerController::class, 'deactivate'])->name('sellers.deactivate');
        Route::post('sellers/{id}/set-pending', [SellerController::class, 'setPending'])->name('sellers.setPending');
        Route::get('produk', [AdminProductController::class, 'index'])->name('produk.index');
        Route::get('produk/{id}', [AdminProductController::class, 'show'])->name('produk.show');
        Route::delete('produk/{id}', [AdminProductController::class, 'destroy'])->name('produk.destroy');
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
        Route::get('saldo-seller', [AdminSaldoController::class, 'index'])->name('saldo.index');
        Route::post('saldo-seller/setujui/{id}', [AdminSaldoController::class, 'approve'])->name('saldo.approve');
        Route::post('saldo-seller/tolak/{id}', [AdminSaldoController::class, 'reject'])->name('saldo.reject');
        Route::resource('kategori', KategoriController::class)->except(['show']);
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
        Route::get('dashboard', [SellerController::class, 'dashboard'])->name('dashboard.index');
        Route::get('produk', [ProductController::class, 'index'])->name('produk.index');
        Route::get('produk/create', [ProductController::class, 'create'])->name('produk.create');
        Route::post('produk', [ProductController::class, 'store'])->name('produk.store');
        Route::get('produk/{produk}/edit', [ProductController::class, 'edit'])->name('produk.edit');
        Route::put('produk/{produk}', [ProductController::class, 'update'])->name('produk.update');
        Route::delete('produk/{produk}', [ProductController::class, 'destroy'])->name('produk.destroy');
        Route::resource('penjualan', PenjualanController::class)->only(['index', 'show']);
        Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo.index');
        Route::post('/saldo/tarik', [SaldoController::class, 'tarikSaldo'])->name('saldo.tarik');
        Route::post('/saldo/update-bank', [SaldoController::class, 'updateBank'])->name('saldo.updateBank');
        Route::get('penghasilan', [PenghasilanController::class, 'index'])->name('penghasilan.index');
        Route::get('ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

// Rute untuk export penghasilan
Route::get('/penghasilan/export', function (Request $request) {
    // Pastikan user login sebagai seller
    $user = auth()->user();
    if (!$user || !$user->seller) {
        abort(403, 'Anda tidak memiliki akses.');
    }

    $sellerId = $user->seller->id;

    // Ambil filter dari request
    $filters = $request->all();

    // Base query
    $query = Transaction::whereHas('product', function ($q) use ($sellerId) {
        $q->where('seller_id', $sellerId);
    });

    // Filter berdasarkan tanggal jika ada
    if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
        $query->whereBetween('transactions.created_at', [
            $filters['start_date'],
            $filters['end_date'],
        ]);
    }

    // Filter berdasarkan status
    if (!empty($filters['status'])) {
        $query->where('transactions.status', $filters['status']);
    }

    // Filter berdasarkan product_id
    if (!empty($filters['product_id'])) {
        $query->where('product_id', $filters['product_id']);
    }

    $transactions = $query->with(['product', 'user'])->get();

    return Excel::download(new PenghasilanExport($transactions), 'laporan_penghasilan.xlsx');
})->name('seller.penghasilan.export');
// Impor rute autentikasi
require __DIR__ . '/auth.php';
