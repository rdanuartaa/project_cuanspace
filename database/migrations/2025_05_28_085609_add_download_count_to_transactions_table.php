<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan kolom download_count jika belum ada
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'download_count')) {
                $table->unsignedInteger('download_count')->default(0);
            }
        });

        // Reset semua nilai download_count ke 0 (opsional)
        DB::table('transactions')->whereNull('download_count')->update(['download_count' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom download_count
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'download_count')) {
                $table->dropColumn('download_count');
            }
        });
    }
};
