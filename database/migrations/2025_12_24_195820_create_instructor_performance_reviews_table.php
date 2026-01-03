<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewed_by_user_id')->constrained('users')->onDelete('cascade');
            $table->date('review_date');
            $table->integer('rating')->nullable(); // 1-5 or similar scale
            $table->text('feedback')->nullable();
            $table->decimal('attendance_score', 5, 2)->nullable();
            $table->decimal('student_feedback_score', 5, 2)->nullable();
            $table->timestamps();
            
            $table->index(['instructor_id', 'review_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_performance_reviews');
    }
};
