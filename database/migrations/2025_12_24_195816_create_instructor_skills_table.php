<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->enum('skill_type', ['style', 'level']);
            $table->string('skill_value');
            $table->integer('proficiency_level')->default(1);
            $table->timestamps();
            
            $table->unique(['instructor_id', 'skill_type', 'skill_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_skills');
    }
};
