<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dojo_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dojo_id')->constrained()->onDelete('cascade');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['dojo_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dojo_settings');
    }
};
