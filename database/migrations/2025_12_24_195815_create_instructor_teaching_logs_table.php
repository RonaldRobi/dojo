<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_teaching_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('hours_taught', 5, 2);
            $table->integer('attendance_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['instructor_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_teaching_logs');
    }
};
