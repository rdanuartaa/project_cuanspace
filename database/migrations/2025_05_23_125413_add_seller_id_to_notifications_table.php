<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSellerIdToNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_id')->nullable()->after('id');

            // Kalau ingin relasi foreign key ke tabel sellers (opsional)
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // drop foreign key dulu kalau ada
            $table->dropForeign(['seller_id']);
            $table->dropColumn('seller_id');
        });
    }
}
