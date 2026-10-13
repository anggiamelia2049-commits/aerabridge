<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lewati bila kolom sudah ada (mis. sudah dibuat dari branch/DB lain)
        if (Schema::hasColumn('notifikasi', 'user_id')) {
            return;
        }

        Schema::table('notifikasi', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->after('id')
                ->constrained('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('notifikasi', 'user_id')) {
            return;
        }

        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};