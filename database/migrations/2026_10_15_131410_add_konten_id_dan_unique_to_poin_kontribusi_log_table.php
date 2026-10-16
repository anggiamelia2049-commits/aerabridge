<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('poin_kontribusi_log', function (Blueprint $table) {
            $table->foreignId('konten_id')
                ->nullable()
                ->after('laporan_id')
                ->constrained('konten_edukasi')
                ->nullOnDelete();

            $table->unsignedInteger('poin_sesudah')
                ->default(0)
                ->after('poin');

            // cegah poin dobel
            $table->unique(['user_id', 'laporan_id', 'jenis_aktivitas'], 'poin_unik_laporan');
            $table->unique(['user_id', 'konten_id', 'jenis_aktivitas'], 'poin_unik_konten');
        });
    }

    public function down(): void
    {
        Schema::table('poin_kontribusi_log', function (Blueprint $table) {
            $table->dropUnique('poin_unik_laporan');
            $table->dropUnique('poin_unik_konten');
            $table->dropForeign(['konten_id']);
            $table->dropColumn(['konten_id', 'poin_sesudah']);
        });
    }
};