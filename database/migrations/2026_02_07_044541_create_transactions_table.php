<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('marketplace')->default('Shopee');
            $table->string('no_pesanan')->unique();
            $table->string('status');
            $table->string('username')->nullable();
            $table->string('kota')->nullable();
            $table->text('alasan_batal')->nullable();
            $table->dateTime('waktu_pesanan');
            $table->dateTime('waktu_pickup')->nullable();
            $table->float('durasi_proses')->nullable(); // Dalam satuan Jam
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};