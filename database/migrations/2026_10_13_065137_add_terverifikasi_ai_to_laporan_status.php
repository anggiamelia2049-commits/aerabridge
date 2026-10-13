<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE laporan MODIFY status ENUM('Menunggu','Terverifikasi AI','Diverifikasi','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Menunggu'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE laporan MODIFY status ENUM('Menunggu','Diverifikasi','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Menunggu'");
    }
};