<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dojo_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('target_audience', ['all', 'students', 'parents', 'instructors'])->default('all');
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->string('attachment_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['dojo_id', 'is_published']);
            $table->index('publish_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
