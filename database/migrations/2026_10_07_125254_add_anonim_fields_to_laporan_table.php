<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('kode_lacak', 20)->nullable()->unique()->after('id');
            $table->boolean('is_anonim')->default(false)->after('kode_lacak');
        });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropUnique(['kode_lacak']);
            $table->dropColumn(['kode_lacak', 'is_anonim']);
        });
}
};
