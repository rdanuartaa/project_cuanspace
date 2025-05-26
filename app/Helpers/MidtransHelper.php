<?php

namespace App\Helpers;

use Midtrans\Config;

class MidtransHelper
{
    public static function config()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
