<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            if (! Schema::hasColumn('laporan', 'tanggal_kejadian')) {
                $table->date('tanggal_kejadian')->nullable()->after('deskripsi');
            }
            if (! Schema::hasColumn('laporan', 'kecamatan')) {
                $table->string('kecamatan', 50)->nullable()->after('tanggal_kejadian');
            }
            if (! Schema::hasColumn('laporan', 'jenis_laporan')) {
                $table->string('jenis_laporan', 30)->nullable()->after('kecamatan');
            }
            if (! Schema::hasColumn('laporan', 'kategori_lainnya')) {
                $table->string('kategori_lainnya', 100)->nullable()->after('jenis_laporan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kejadian', 'kecamatan', 'jenis_laporan', 'kategori_lainnya']);
        });
    }
};
