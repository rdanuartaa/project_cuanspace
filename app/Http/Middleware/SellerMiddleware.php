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
        
        if (Auth::check() && Auth::user()->seller && Auth::user()->seller->status === 'pending') {
            return redirect()->route('main.home')->with('status', 'Akun seller Anda sedang dalam proses verifikasi. Mohon tunggu konfirmasi dari admin.');
        }
        
        return redirect()->route('main.home')->with('status', 'Anda perlu mendaftar sebagai seller terlebih dahulu.');
    }
}