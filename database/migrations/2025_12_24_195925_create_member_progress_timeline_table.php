<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_progress_timeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('event_type', ['rank_up', 'progress_log', 'grading']);
            $table->date('event_date');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('related_id')->nullable(); // ID of related record (rank, progress_log, grading)
            $table->timestamps();
            
            $table->index(['member_id', 'event_date']);
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_progress_timeline');
    }
};
