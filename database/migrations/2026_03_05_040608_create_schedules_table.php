<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel guru
            $table->foreignId('guru_id')->constrained('guru')->onDelete('cascade');

            // Relasi ke tabel classes (XI RPL 1, dsb)
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');

            $table->string('day'); // Senin, Selasa, dsb
            $table->string('subject'); // Nama Mata Pelajaran
            $table->time('time_start'); // Jam Mulai
            $table->time('time_end');   // Jam Selesai
            $table->string('room')->nullable(); // Ruangan (opsional)
            
            $table->boolean('is_break')->default(false); // Menandai jika itu jam istirahat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};