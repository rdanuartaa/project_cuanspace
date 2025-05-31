<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\MidtransHelper;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * Handle callback dari Midtrans (Server-to-Server)
     */
    public function callback(Request $request)
    {
        try {
            // Set konfigurasi Midtrans
            MidtransHelper::config();

            // Terima notifikasi dari Midtrans
            $notification = new Notification();

            $status = $notification->transaction_status;
            $orderId = $notification->order_id;

            // Log data notifikasi
            Log::info("Callback Midtrans diterima", [
                'order_id' => $orderId,
                'transaction_status' => $status,
                'raw_data' => json_encode($notification),
            ]);

            // Cari transaksi berdasarkan kode transaksi
            $transaction = Transaction::where('transaction_code', $orderId)->first();

            if (!$transaction) {
                Log::warning("Transaksi tidak ditemukan", ['order_id' => $orderId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            // Mapping status dari Midtrans
            $newStatus = match ($status) {
                'settlement', 'capture' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'cancelled',
                'deny' => 'failed',
                default => 'unknown',
            };

            // Update transaksi
            $transaction->update([
                'status' => $newStatus,
                'payment_type' => $notification->payment_type ?? null,
                'payment_time' => $notification->transaction_time ?? null,
                'midtrans_response' => json_encode($notification),
            ]);

            // 🔁 Update balance seller jika transaksi sukses
            if ($newStatus === 'paid') {
                if ($transaction->product && $transaction->product->seller) {
                    $transaction->product->seller->updateBalance();
                    Log::info("Saldo seller diperbarui", [
                        'seller_id' => $transaction->product->seller->id,
                        'amount' => $transaction->amount,
                        'transaction_code' => $orderId,
                    ]);
                }
            }

            // Balas dengan respons JSON atau string biasa agar Midtrans terima
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil diproses',
                'order_id' => $orderId,
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            Log::error("Error saat memproses callback Midtrans: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses notifikasi'
            ], 500);
        }
    }
}
