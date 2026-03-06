<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // 1. Ubah status jadi string agar tidak error 'Data Truncated' lagi
            $table->string('status')->change();

            // 2. Tambahkan kolom keterangan jika belum ada
            if (!Schema::hasColumn('attendances', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('status');
            }

            // 3. Pastikan guru_id boleh kosong (null)
            $table->unsignedBigInteger('guru_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
