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
        Schema::table('jadwal_guru', function (Blueprint $table) {
            $table->integer('batas_telat')->default(5)->after('jam_selesai')->comment('Batas menit untuk status telat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_guru', function (Blueprint $table) {
            $table->dropColumn('batas_telat');
        });
    }
};
