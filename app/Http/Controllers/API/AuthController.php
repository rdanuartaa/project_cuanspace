<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Log;
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token', ['*'], now()->addDays(7))->plainTextToken;

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                Log::warning("Login failed for email: {$request->email}, invalid credentials");
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kredensial tidak valid.',
                ], 401);
            }
            $user->tokens()->delete();
            $token = $user->createToken('auth_token', ['*'], now()->addDays(7))->plainTextToken;
            Log::info("Login berhasil untuk user_id: {$user->id}, token: $token");
            $seller = Seller::where('user_id', $user->id)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully logged in',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
                'is_seller' => !!$seller,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Login error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat login: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete();
            Log::info("Logout berhasil untuk user_id: {$user->id}");
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ], 200);
    }
}
