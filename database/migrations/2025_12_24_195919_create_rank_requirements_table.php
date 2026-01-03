<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rank_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rank_id')->constrained()->onDelete('cascade');
            $table->enum('requirement_type', ['attendance_min', 'exam_required', 'recommendation_required']);
            $table->string('requirement_value');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('rank_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rank_requirements');
    }
};
