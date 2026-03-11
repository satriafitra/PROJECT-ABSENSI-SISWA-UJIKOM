<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assessments', function (Blueprint $col) {
            // 1. Hapus foreign key yang lama (yang ke arah users)
            $col->dropForeign(['evaluatee_id']);
            
            // 2. Buat foreign key baru yang mengarah ke tabel students
            $col->foreign('evaluatee_id')
                ->references('id')
                ->on('students')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('assessments', function (Blueprint $col) {
            $col->dropForeign(['evaluatee_id']);
            $col->foreign('evaluatee_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};