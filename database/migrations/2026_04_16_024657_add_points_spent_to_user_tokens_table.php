<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            $table->integer('points_spent')->nullable()->after('item_id');
        });
    }

    public function down(): void
    {
        Schema::table('user_tokens', function (Blueprint $table) {
            $table->dropColumn('points_spent');
        });
    }
};