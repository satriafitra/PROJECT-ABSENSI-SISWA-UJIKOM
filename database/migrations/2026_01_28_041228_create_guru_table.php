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
        Schema::create('guru', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('nip')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            // 🔐 PASSWORD (WAJIB UNTUK LOGIN)
            $table->string('password');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
