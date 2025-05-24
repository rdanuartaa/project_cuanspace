<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWithdrawsTableForeignKey extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('withdraws', function (Blueprint $table) {
            // Hapus foreign key lama jika ada
            $table->dropForeign(['seller_id']);

            // Perbarui kolom seller_id agar merujuk ke sellers.id
            $table->foreign('seller_id')
                  ->references('id')->on('sellers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdraws', function (Blueprint $table) {
            // Kembalikan foreign key ke users.id (jika rollback)
            $table->dropForeign(['seller_id']);

            $table->foreign('seller_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }
}
