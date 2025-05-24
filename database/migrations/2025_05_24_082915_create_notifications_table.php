<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('notifications');

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('judul', 100);
            $table->text('pesan');
            $table->enum('penerima', ['semua', 'seller', 'pengguna', 'khusus']);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('seller_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['terkirim'])->change();
            $table->boolean('read')->default(false);
            $table->foreignId('chat_id')->nullable()->constrained('chats')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
