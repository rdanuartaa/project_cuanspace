<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Seller;
use App\Observers\SellerObserver;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Models\Withdraw;
use App\Observers\WithdrawObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Seller::observe(SellerObserver::class);
        Transaction::observe(TransactionObserver::class);
        Withdraw::observe(WithdrawObserver::class);
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}

