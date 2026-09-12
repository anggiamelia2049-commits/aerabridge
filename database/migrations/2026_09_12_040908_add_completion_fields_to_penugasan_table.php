<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom yang dibutuhkan untuk closing report petugas,
     * yang belum ada pada migration awal tabel `penugasan`.
     */
    public function up(): void
    {
        Schema::table('penugasan', function (Blueprint $table) {
            $table->string('foto_hasil')->nullable()->after('catatan');
            $table->text('catatan_penyelesaian')->nullable()->after('foto_hasil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penugasan', function (Blueprint $table) {
            $table->dropColumn(['foto_hasil', 'catatan_penyelesaian']);
        });
    }
};