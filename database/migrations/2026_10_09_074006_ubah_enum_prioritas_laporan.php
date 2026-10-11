<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Lebarkan enum supaya memuat ejaan lama dan baru
        DB::statement("ALTER TABLE laporan MODIFY tingkat_prioritas ENUM('Krisis','Kritis','Sedang','Rendah') NOT NULL DEFAULT 'Sedang'");

        // 2. Ubah data lama ke ejaan baru
        DB::table('laporan')->where('tingkat_prioritas', 'Krisis')->update(['tingkat_prioritas' => 'Kritis']);

        // 3. Persempit enum ke versi final
        DB::statement("ALTER TABLE laporan MODIFY tingkat_prioritas ENUM('Kritis','Sedang','Rendah') NOT NULL DEFAULT 'Sedang'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE laporan MODIFY tingkat_prioritas ENUM('Krisis','Kritis','Sedang','Rendah') NOT NULL DEFAULT 'Sedang'");
        DB::table('laporan')->where('tingkat_prioritas', 'Kritis')->update(['tingkat_prioritas' => 'Krisis']);
        DB::statement("ALTER TABLE laporan MODIFY tingkat_prioritas ENUM('Krisis','Sedang','Rendah') NOT NULL DEFAULT 'Sedang'");
    }
};