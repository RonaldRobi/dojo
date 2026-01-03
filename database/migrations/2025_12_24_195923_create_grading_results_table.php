<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('rank_id')->constrained()->onDelete('cascade');
            $table->date('grading_date');
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade');
            $table->decimal('exam_score', 5, 2)->nullable();
            $table->text('recommendation')->nullable();
            $table->enum('status', ['passed', 'failed'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'grading_date']);
            $table->index('rank_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_results');
    }
};
