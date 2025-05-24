<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('penerima')->default('semua')->after('pesan');
            $table->json('user_ids')->nullable()->after('penerima');
            $table->json('seller_brand_names')->nullable()->after('user_ids');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['penerima', 'user_ids', 'seller_brand_names']);
        });
    }
}
