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
        Schema::create('penugasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tim_satgas_id')->constrained('tim_satgas')->cascadeOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users');
            $table->enum('status', ['ditugaskan','dalam_proses','selesai','dibatalkan'])->default('ditugaskan');
            $table->dateTime('tanggal_penugasan');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan');
    }
};
