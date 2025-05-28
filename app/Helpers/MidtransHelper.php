<?php

// App/Helpers/MidtransHelper.php
namespace App\Helpers;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction as MidtransTransaction;

class MidtransHelper
{
    public static function config()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY'); // Tambahkan client key
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public static function getSnapToken($params)
    {
        self::config();
        return Snap::getSnapToken($params);
    }

    public static function handleNotification()
    {
        self::config();
        $notification = new \Midtrans\Notification();
        return [
            'order_id' => $notification->order_id,
            'transaction_status' => $notification->transaction_status,
            'fraud_status' => $notification->fraud_status ?? null,
            'payment_type' => $notification->payment_type ?? null,
        ];
    }
}
