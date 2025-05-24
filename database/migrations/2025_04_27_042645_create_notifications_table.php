<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
        $table->string('judul', 100);
        $table->text('pesan');
        $table->enum('pelaku', ['semua', 'seller', 'pengguna', 'khusus']);
        $table->unsignedBigInteger('user_id')->nullable(); // untuk pelaku = khusus
        $table->enum('status', ['terkirim'])->default('terkirim');
        $table->json('seller_brand_names')->nullable();
        $table->dateTime('jadwal_kirim')->nullable();
        $table->timestamps();
    });
}
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        
    }
};
