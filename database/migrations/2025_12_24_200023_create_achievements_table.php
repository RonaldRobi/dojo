<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dojo_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->date('achieved_date');
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index(['dojo_id', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
