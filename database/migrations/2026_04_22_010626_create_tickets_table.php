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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('students')->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->enum('priority', ['Low', 'Mid', 'High'])->default('Low');
            $table->enum('status', ['Open', 'In-Progress', 'Closed'])->default('Open');
            $table->timestamps();

            // Full text index for duplicate checking
            $table->fullText(['subject', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
