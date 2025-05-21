<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Membuat user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Membuat token dengan kemampuan (abilities) opsional dan waktu kedaluwarsa (opsional)
        $token = $user->createToken('auth_token', ['*'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Mencoba melakukan autentikasi menggunakan kredensial
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided credentials are incorrect'
            ], 401);
        }

        $user = Auth::user();

        // Hapus semua token lama
        $user->tokens()->delete();

        // Membuat token baru dengan kemampuan dan waktu kedaluwarsa (opsional)
        $token = $user->createToken('auth_token', ['*'])->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged in',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        // Mendapatkan pengguna yang sedang login
        $user = Auth::user();

        // Menghapus semua token Sanctum pengguna
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }
}
