<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalancesTable extends Migration
{
    public function up()
    {
        Schema::create('balances', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2); // Untuk menyimpan saldo dalam Rupiah
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('balances');
    }
}
