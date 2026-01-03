<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_brackets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('bracket_name');
            $table->foreignId('category_id')->nullable()->constrained('event_categories')->onDelete('cascade');
            $table->json('participants')->nullable();
            $table->json('bracket_data')->nullable();
            $table->timestamps();
            
            $table->index('event_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_brackets');
    }
};
