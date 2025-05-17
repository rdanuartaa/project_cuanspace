<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            // Validasi email
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            // Generate OTP
            $otp = rand(100000, 999999); // 6-digit OTP
            $expiresAt = now()->addMinutes(10);

            // Simpan OTP ke database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'otp' => $otp,
                    'otp_expires_at' => $expiresAt,
                    'created_at' => now(),
                ]
            );

            // Kirim email OTP
            Mail::raw("Kode OTP Anda adalah: $otp. Kode ini berlaku selama 10 menit.", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Kode OTP untuk Reset Password')
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

            return response()->json([
                'message' => 'Kode OTP telah dikirim ke email Anda.',
                'status' => 200
            ], 200);

        } catch (ValidationException $e) {
            // Tangani error validasi khusus
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
                'status' => 422
            ], 422);

        } catch (\Exception $e) {
            // Tangani error lainnya
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
