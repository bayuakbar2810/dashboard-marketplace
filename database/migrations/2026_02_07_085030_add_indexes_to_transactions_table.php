<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menambahkan Index agar pencarian & grouping super cepat
            $table->index('marketplace');
            $table->index('status');
            $table->index('username');
            $table->index('nama_gudang');
            $table->index('waktu_pesanan'); // Penting untuk sorting
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['marketplace', 'status', 'username', 'nama_gudang', 'waktu_pesanan']);
        });
    }
};