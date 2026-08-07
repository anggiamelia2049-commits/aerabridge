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
        Schema::create('deteksi_ai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained()->cascadeOnDelete();
            $table->string('jenis_objek');
            $table->decimal('confidence');
            $table->enum('tingkat_kerusakan', ['ringan','sedang','berat']);
            $table->enum('estimasi_prioritas', ['kritis','sedang','rendah']);
            $table->enum('hasil_validasi', ['valid','tidak_valid']);
            $table->text('response_llm')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deteksi_ai');
    }
};
