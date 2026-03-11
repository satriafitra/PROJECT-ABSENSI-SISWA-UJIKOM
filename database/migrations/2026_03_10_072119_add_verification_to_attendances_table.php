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
            $table->string('image')->nullable()->after('keterangan');
            $table->enum('is_verified', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('image');
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['image', 'is_verified']);
        });
    }
};
