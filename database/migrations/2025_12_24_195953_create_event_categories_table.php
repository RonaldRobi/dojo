<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('category_name');
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();
            $table->integer('level_min')->nullable();
            $table->integer('level_max')->nullable();
            $table->enum('gender_filter', ['male', 'female', 'all'])->default('all');
            $table->timestamps();
            
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_categories');
    }
};
