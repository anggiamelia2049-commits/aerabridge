<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // cegah +10 poin didapat berkali-kali dari konten yang sama
        Schema::table('user_edukasi_progress', function (Blueprint $table) {
            $table->unique(['user_id', 'konten_id'], 'progress_unik_user_konten');
        });

        // catat poin yang ditukar (1.000 poin = Rp500)
        Schema::table('aera_pay_transaksi', function (Blueprint $table) {
            $table->unsignedInteger('poin_ditukar')->default(0)->after('nominal');
        });
    }

    public function down(): void
    {
        Schema::table('aera_pay_transaksi', function (Blueprint $table) {
            $table->dropColumn('poin_ditukar');
        });

        Schema::table('user_edukasi_progress', function (Blueprint $table) {
            $table->dropUnique('progress_unik_user_konten');
        });
    }
};
