<?php


namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Seller;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Bagikan data seller ke layout 'layouts.seller'
        View::composer('layouts.seller', function ($view) {
            $user = Auth::user();

            if ($user && $user->seller) {
                $seller = $user->seller;
            } else {
                $seller = null;
            }

            $view->with('seller', $seller);
        });
    }

    public function register()
    {
        //
    }
}
