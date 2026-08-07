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
        Schema::create('sla_konfigurasi', function (Blueprint $table) {
            $table->id();
            $table->enum('prioritas', ['kritis','sedang','rendah']);
            $table->integer('waktu_respon');
            $table->integer('waktu_penyelesaian');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif','nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sla_konfigurasi');
    }
};
