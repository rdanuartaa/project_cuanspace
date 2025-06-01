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
            $notification = MidtransHelper::handleNotification();

            $orderId = $notification['order_id'];
            $status = $notification['transaction_status'];

            Log::info("Callback Midtrans diterima", [
                'order_id' => $orderId,
                'transaction_status' => $status,
                'notification' => $notification,
            ]);

            $transaction = Transaction::where('transaction_code', $orderId)->first();

            if (!$transaction) {
                Log::warning("Transaksi tidak ditemukan", ['order_id' => $orderId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            $newStatus = match ($status) {
                'settlement', 'capture' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'cancelled',
                'deny' => 'failed',
                default => 'unknown',
            };

            $transaction->update([
                'status' => $newStatus,
                'payment_type' => $notification['payment_type'] ?? null,
                'payment_time' => $notification['transaction_time'] ?? now(),
                'midtrans_response' => json_encode($notification),
            ]);

            if ($newStatus === 'paid') {
                $product = $transaction->product;
                if (!$product) {
                    Log::warning("Produk tidak ditemukan untuk transaksi", ['transaction_id' => $transaction->id]);
                } else {
                    $seller = $product->seller;
                    if ($seller) {
                        $seller->updateBalance();
                        Log::info("Saldo seller diperbarui", [
                            'seller_id' => $seller->id,
                            'amount' => $transaction->amount,
                            'transaction_code' => $orderId,
                        ]);
                    }
                }
            }

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
