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
        Schema::table('flexibility_items', function (Blueprint $table) {
            $table->integer('extra_minutes')->nullable();
            $table->boolean('is_voucher')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flexibility_items', function (Blueprint $table) {
            $table->dropColumn(['extra_minutes', 'is_voucher']);
        });
    }
};
