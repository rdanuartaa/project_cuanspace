<?php

// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;
use App\Models\Seller;

class AdminController extends Controller
{
    /**
     * Menampilkan form login
     */
    public function showLoginForm()
    {
        return view('main.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Cek apakah email yang dimasukkan adalah milik admin
        if ($this->isAdmin($request->email)) {
            // Login menggunakan guard admin
            if (Auth::guard('admin')->attempt($credentials)) {
                return redirect()->intended('/admin/dashboard');
            }
        } else {
            // Login menggunakan guard user
            if (Auth::guard('web')->attempt($credentials)) {
                return redirect()->intended('/home');
            }
        }

        // Jika login gagal, kembalikan dengan error
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // Invalidate session dan regenerate CSRF token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Mengecek apakah email admin
     */
    private function isAdmin($email)
    {
        $admin = Admin::where('email', $email)->first();
        return $admin !== null;
    }

    /**
     * Menampilkan dashboard admin
     */
    public function dashboard()
{
    // Ambil seller
    $sellers = Seller::all();
    $topSellers = Seller::where('status', 'active')
        ->orderBy('updated_at', 'desc')
        ->take(5)
        ->get();

    // Ambil admin yang login
    $admin = Auth::guard('admin')->user(); // atau Auth::user('admin')

    // Kirim ke view
    return view('admin.dashboard', compact('sellers', 'topSellers', 'admin'));
}
}
