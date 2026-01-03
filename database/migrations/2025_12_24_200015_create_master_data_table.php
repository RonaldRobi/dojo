<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dojo_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['level', 'belt', 'style', 'category']);
            $table->string('name');
            $table->string('code')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['dojo_id', 'type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_data');
    }
};
