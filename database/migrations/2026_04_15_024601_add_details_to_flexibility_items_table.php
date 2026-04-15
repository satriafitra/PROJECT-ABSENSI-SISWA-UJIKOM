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
            // Menambahkan kolom baru ke migrasi yang sudah Anda punya
            $table->string('category')->default('Umum')->after('item_name');
            $table->text('description')->nullable()->after('category');
            $table->string('icon')->default('ticket')->after('stock_limit');
            $table->boolean('is_active')->default(true)->after('icon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flexibility_items', function (Blueprint $table) {
            //
        });
    }
};
