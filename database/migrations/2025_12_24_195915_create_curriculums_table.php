<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rank_id')->constrained()->onDelete('cascade');
            $table->string('skill_name');
            $table->text('description')->nullable();
            $table->integer('order');
            $table->boolean('is_required')->default(true);
            $table->timestamps();
            
            $table->index(['rank_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculums');
    }
};
