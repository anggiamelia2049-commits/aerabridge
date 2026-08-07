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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategori_kerusakan')->cascadeOnDelete();
            $table->foreignId('instansi_id')->constrained('instansi')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->decimal('latitude', 10,8);
            $table->decimal('longitude', 11,8);
            $table->text('alamat')->nullable();
            $table->enum('tingkat_prioritas', ['Krisis','Sedang','Rendah'])->default('Sedang');
            $table->enum('status', ['Menunggu','Diverifikasi', 'Diproses', 'Selesai', 'Ditolak'])->default('Menunggu');
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
