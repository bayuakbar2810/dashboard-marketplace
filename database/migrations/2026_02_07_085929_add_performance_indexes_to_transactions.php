<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Daftar kolom yang mau kita percepat (Index)
        $columns = ['marketplace', 'status', 'username', 'nama_gudang', 'waktu_pesanan'];

        Schema::table('transactions', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                // Nama index standar: namatabel_namakolom_index
                $indexName = "transactions_{$column}_index";

                // LOGIKA PINTAR:
                // Cek dulu: "Apakah index ini SUDAH ADA?"
                // Kalau BELUM ADA (!), baru kita buat.
                if (!Schema::hasIndex('transactions', $indexName)) {
                    $table->index($column, $indexName);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus index kalau rollback
            $table->dropIndex(['marketplace', 'status', 'username', 'nama_gudang', 'waktu_pesanan']);
        });
    }
};