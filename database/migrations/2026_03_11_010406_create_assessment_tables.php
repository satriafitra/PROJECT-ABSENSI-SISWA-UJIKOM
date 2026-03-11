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
        // 1. Kategori Penilaian (Dinamis)
        Schema::create('assessment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Cth: Disiplin, Kerjasama [cite: 35]
            $table->text('description')->nullable();
            $table->string('type')->default('Student'); // [cite: 37]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Header Transaksi Penilaian
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluator_id')->constrained('users'); // Guru [cite: 41]
            $table->foreignId('evaluatee_id')->constrained('users'); // Siswa [cite: 42]
            $table->date('assessment_date');
            $table->string('period'); // Cth: Semester 1 [cite: 44]
            $table->text('general_notes')->nullable();
            $table->timestamps();
        });

        // 3. Detail Nilai per Kategori
        Schema::create('assessment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('assessment_categories');
            $table->integer('score'); // Skala 1-5 [cite: 50]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_tables');
    }
};
