<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToNotificationsTable extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->boolean('read')->default(false)->after('jadwal_kirim');
            $table->unsignedBigInteger('chat_id')->nullable()->after('read');
            $table->unsignedBigInteger('seller_id')->nullable()->after('chat_id');

            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('set null');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['chat_id']);
            $table->dropForeign(['seller_id']);
            $table->dropColumn(['read', 'chat_id', 'seller_id']);
        });
    }
}
