<?php

namespace App\Helpers;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;
use Illuminate\Support\Facades\Log;

class MidtransHelper
{
    public static function config()
    {
        Config::$clientKey = config('midtrans.client_key');
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);

        // Validasi konfigurasi
        if (empty(Config::$clientKey) || empty(Config::$serverKey)) {
            Log::error('Kunci Midtrans tidak ditemukan', [
                'client_key' => Config::$clientKey ? 'Set' : 'Not Set',
                'server_key' => Config::$serverKey ? 'Set' : 'Not Set',
            ]);
            throw new \Exception('Kunci Midtrans (ClientKey atau ServerKey) tidak dikonfigurasi dengan benar.');
        }
    }

    public static function testConfig()
    {
        try {
            self::config();
            Log::info("Midtrans Config", ['serverKey' => Config::$serverKey ? 'Set' : 'Not Set']);
            return response()->json(['serverKey' => Config::$serverKey ? 'Set' : 'Not Set']);
        } catch (\Exception $e) {
            Log::error("Gagal menguji konfigurasi Midtrans: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getSnapToken($params)
    {
        self::config();
        try {
            $snapToken = Snap::getSnapToken($params);
            Log::info('Midtrans params', ['params' => $params]);
            Log::info("Raw snap token dari Midtrans: ", ['snap_token' => $snapToken]);

            // Jika snapToken adalah string, cek apakah itu JSON
            if (is_string($snapToken)) {
                $decoded = json_decode($snapToken, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    if (isset($decoded['snap_token'])) {
                        $snapToken = $decoded['snap_token'];
                        Log::info("Snap token diekstrak dari JSON string: ", ['snap_token' => $snapToken]);
                    } else {
                        Log::error("String JSON tidak memiliki key 'snap_token'", ['snap_token' => $snapToken]);
                        throw new \Exception("Snap token tidak ditemukan dalam respons JSON dari Midtrans");
                    }
                } else {
                    Log::info("Snap token sudah dalam format string (bukan JSON): ", ['snap_token' => $snapToken]);
                }
            }

            // Jika snapToken adalah array atau object, ekstrak token
            if (is_array($snapToken) || is_object($snapToken)) {
                if (isset($snapToken['snap_token'])) {
                    $snapToken = $snapToken['snap_token'];
                    Log::info("Snap token diekstrak dari array/object: ", ['snap_token' => $snapToken]);
                } else {
                    Log::error("Snap token dalam format array/object tetapi tidak memiliki key 'snap_token'", ['snap_token' => $snapToken]);
                    throw new \Exception("Snap token tidak ditemukan dalam respons array/object dari Midtrans");
                }
            }

            // Pastikan snapToken adalah string setelah ekstraksi
            if (!is_string($snapToken)) {
                Log::error("Snap token tetap bukan string setelah ekstraksi", ['snap_token' => $snapToken]);
                throw new \Exception("Snap token harus berupa string");
            }

            Log::info("Snap token akhir yang dikembalikan: ", ['snap_token' => $snapToken]);
            return $snapToken;
        } catch (\Exception $e) {
            Log::error("Gagal membuat snap token: " . $e->getMessage(), ['params' => $params]);
            throw new \Exception("Gagal membuat snap token: " . $e->getMessage());
        }
    }

    public static function handleNotification()
    {
        self::config();
        try {
            $notification = new \Midtrans\Notification();
            $data = [
                'order_id' => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'fraud_status' => $notification->fraud_status ?? null,
                'payment_type' => $notification->payment_type ?? null,
                'transaction_time' => $notification->transaction_time ?? null,
            ];
            Log::info("Notifikasi Midtrans berhasil diproses", ['data' => $data]);
            return $data;
        } catch (\Exception $e) {
            Log::error("Gagal memproses notifikasi Midtrans: " . $e->getMessage());
            throw $e;
        }
    }
}
