<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE laporan MODIFY tingkat_prioritas ENUM('Kritis','Sedang','Rendah') NOT NULL DEFAULT 'Sedang'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE laporan MODIFY tingkat_prioritas ENUM('Krisis','Sedang','Rendah') NOT NULL DEFAULT 'Sedang'");
    }
};