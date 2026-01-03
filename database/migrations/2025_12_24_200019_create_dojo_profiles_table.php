<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dojo_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dojo_id')->unique()->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->text('history')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->text('achievements')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('social_media_links')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dojo_profiles');
    }
};
