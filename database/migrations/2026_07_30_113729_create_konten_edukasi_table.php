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
        Schema::create('konten_edukasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('thumbnail')->nullable();
            $table->text('isi');
            $table->string('kategori');
            $table->foreignId('super_admin')->constrained('users');
            $table->enum('status', ['draft','publish','nonaktif'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_edukasi');
    }
};
