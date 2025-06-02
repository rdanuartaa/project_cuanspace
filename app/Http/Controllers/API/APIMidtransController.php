<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\MidtransHelper;
use Midtrans\Notification;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class APIMidtransController extends Controller
{
    /**
     * Handle callback dari Midtrans (Server-to-Server)
     */
    public function callback(Request $request)
    {
        try {
            MidtransHelper::config();
            $notification = new Notification();

            $status = $notification->transaction_status;
            $orderId = $notification->order_id;

            Log::info("Callback Midtrans diterima", [
                'order_id' => $orderId,
                'transaction_status' => $status,
                'raw_data' => json_encode($notification),
                'request_headers' => $request->headers->all(),
                'request_body' => $request->all(),
            ]);

            $transaction = Transaction::where('transaction_code', $orderId)->first();
            if (!$transaction) {
                Log::warning("Transaksi tidak ditemukan di database", [
                    'order_id' => $orderId,
                    'transaction_exists' => Transaction::where('transaction_code', $orderId)->exists(),
                    'database_transactions' => Transaction::where('transaction_code', $orderId)->get()->toArray(),
                ]);
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            $newStatus = match ($status) {
                'settlement', 'capture' => 'paid',
                'pending' => 'pending',
                'expire' => 'expired',
                'cancel' => 'cancelled',
                'deny' => 'failed',
                default => 'unknown',
            };

            // Hanya perbarui jika status berubah untuk menghindari pembaruan berulang
            if ($transaction->status !== $newStatus) {
                $transaction->update([
                    'status' => $newStatus,
                    'payment_type' => $notification->payment_type ?? null,
                    'payment_time' => $notification->transaction_time ?? null,
                    'midtrans_response' => json_encode($notification),
                ]);

                Log::info("Status transaksi diperbarui", [
                    'order_id' => $orderId,
                    'new_status' => $newStatus,
                    'transaction_id' => $transaction->id,
                ]);

                if ($newStatus === 'paid' && $transaction->product && $transaction->product->seller) {
                    $transaction->product->seller->updateBalance();
                    Log::info("Saldo seller diperbarui", [
                        'seller_id' => $transaction->product->seller->id,
                        'amount' => $transaction->amount,
                        'transaction_code' => $orderId,
                    ]);
                }
            }

            return response()->json(['message' => 'Notifikasi diproses']);
        } catch (\Exception $e) {
            Log::error("Error saat memproses callback Midtrans: " . $e->getMessage(), [
                'request' => $request->all(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Gagal memproses notifikasi'], 500);
        }
    }

    public function testSnapToken(Request $request)
    {
        try {
            $params = [
                'transaction_details' => [
                    'order_id' => 'TEST-' . Str::random(8),
                    'gross_amount' => 10000,
                ],
                'customer_details' => [
                    'first_name' => 'Test',
                    'email' => 'test@example.com',
                ],
                'item_details' => [[
                    'id' => 1,
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Test Product',
                ]],
            ];
            $snapToken = MidtransHelper::getSnapToken($params);
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error("Test snap token error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
