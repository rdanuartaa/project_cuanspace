<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Admin;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Ambil email dari request
        $credentials = $request->only('email', 'password');

        // Cek apakah email yang dimasukkan milik admin
        if ($this->isAdmin($request->email)) {
            // Jika admin, coba login dengan guard 'admin'
            if (Auth::guard('admin')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard')); // Redirect ke dashboard admin
            }
        } else {
            // Jika user biasa, coba login dengan guard 'web'
            if (Auth::guard('web')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('main.home')); // Redirect ke halaman home untuk user
            }
        }

        // Jika login gagal, kembali dengan error
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout admin atau user berdasarkan guard yang digunakan
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::guard('web')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Menentukan apakah email adalah milik admin
     */
    private function isAdmin($email)
    {
        return Admin::where('email', $email)->exists(); // Cek apakah email milik admin
    }
}

