<?php
// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use App\Models\User;

class AdminController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('main.login');  // Menampilkan form login
    }

    // Proses login
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

    // Logout admin
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');  // Redirect ke halaman depan setelah logout
    }

    // Mengecek apakah email admin
    private function isAdmin($email)
    {
        $admin = Admin::where('email', $email)->first();
        return $admin !== null;  // Jika admin ditemukan
    }
}


