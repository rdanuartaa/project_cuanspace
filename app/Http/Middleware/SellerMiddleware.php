<?php

// app/Http/Middleware/SellerMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->seller && Auth::user()->seller->status === 'active') {
            return $next($request);
        }

        return redirect()->route('main.home'); // Jika status seller belum aktif, redirect ke halaman home
    }
}

