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
        Schema::create('student_evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_evaluation_id')->constrained('student_evaluations')->cascadeOnDelete();
            $table->text('question');
            $table->enum('type', ['text', 'multiple_choice', 'rating']);
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_evaluation_questions');
    }
};
