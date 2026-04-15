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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete(); // Pastikan ini ada
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            // Tambahkan dua baris ini di file migration kamu
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->enum('status', ['hadir', 'telat', 'alfa', 'sakit', 'izin'])->default('hadir');
            $table->string('keterangan')->nullable();
            $table->string('image')->nullable();
            $table->enum('is_verified', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
