<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MidtransHelper;
use Midtrans\Notification;
use App\Models\Transaction;


class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        MidtransHelper::config();
        $notification = new Notification();

        $status = $notification->transaction_status;
        $orderId = $notification->order_id;

        $transaction = Transaction::where('transaction_code', $orderId)->first();

        if (!$transaction) return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        if ($status == 'capture' || $status == 'settlement') {
            $transaction->update(['status' => 'paid']);
        } elseif ($status == 'pending') {
            $transaction->update(['status' => 'pending']);
        } elseif ($status == 'cancel' || $status == 'expire' || $status == 'deny') {
            $transaction->update(['status' => 'failed']);
        }
        return response()->json(['message' => 'Notifikasi diproses']);
    }
}
