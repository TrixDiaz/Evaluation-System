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
        Schema::create('copus_observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->integer('observation_number');
            $table->string('observer_name');
            $table->date('observation_date');
            $table->string('course_name');
            $table->json('student_activities');
            $table->json('instructor_activities');
            $table->json('comments')->nullable();
            $table->text('additional_comments')->nullable();
            $table->timestamps();

            $table->unique(['schedule_id', 'observation_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copus_observations');
    }
};
