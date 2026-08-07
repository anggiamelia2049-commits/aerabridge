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
        Schema::create('hadiah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_hadiah', 150);
            $table->text('deskripsi')->nullable();
            $table->integer('poin_dibutuhkan');
            $table->integer('stok')->default(0);
            $table->string('gambar')->nullable();
            $table->enum('status', ['tersedia','habis','nonaktif'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hadiah');
    }
};
