<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Cek OTP di tabel password_reset_tokens
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$resetRecord) {
            throw ValidationException::withMessages([
                'otp' => ['OTP tidak valid.'],
            ]);
        }

        // Cek apakah OTP sudah kedaluwarsa
        if (now()->gt($resetRecord->otp_expires_at)) {
            throw ValidationException::withMessages([
                'otp' => ['OTP telah kedaluwarsa.'],
            ]);
        }

        // Reset password
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        $user->forceFill([
            'password' => bcrypt($request->password),
        ])->save();

        // Hapus OTP setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password telah berhasil direset.',
        ], 200);
    }
}
