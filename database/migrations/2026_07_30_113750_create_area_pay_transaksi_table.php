<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('area_pay_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('laporan_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('jenis_transaksi', ['reward','redeem','admin_adjust']);
            $table->integer('nominal');
            $table->integer('saldo_sebelum');
            $table->integer('saldo_sesudah');
            $table->enum('status', ['berhasil','pending','gagal'])->default('berhasil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_pay_transaksi');
    }
};
