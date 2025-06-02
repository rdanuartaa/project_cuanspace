<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $otpExpiresAt = now()->addMinutes(10); // OTP berlaku 10 menit

        // Hapus token lama untuk email ini
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Simpan OTP dan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Str::random(60), // Generate token unik
            'otp' => $otp,
            'otp_expires_at' => $otpExpiresAt,
            'created_at' => now(),
        ]);

        // Kirim OTP ke email pengguna
        try {
            Mail::to($request->email)->send(new SendOtpMail($otp));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengirim email. Silakan coba lagi nanti.',
                'error' => $e->getMessage(),
            ], 500);
        }

        // Kembalikan respons tanpa OTP untuk keamanan
        return response()->json([
            'message' => 'Permintaan reset kata sandi telah dikirim. Silakan cek email Anda untuk kode OTP.',
        ], 200);
    }
}   
