<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('instructors')->onDelete('cascade');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->text('skills_improved')->nullable();
            $table->text('areas_to_improve')->nullable();
            $table->json('curriculum_items_completed')->nullable();
            $table->timestamps();
            
            $table->index(['member_id', 'date']);
            $table->index('instructor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_logs');
    }
};
