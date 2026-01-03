<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_ranks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('rank_id')->constrained()->onDelete('cascade');
            $table->timestamp('achieved_at');
            $table->foreignId('awarded_by_instructor_id')->nullable()->constrained('instructors')->onDelete('set null');
            $table->string('certificate_url')->nullable();
            $table->unsignedBigInteger('grading_event_id')->nullable(); // Foreign key to events table
            $table->timestamps();
            
            $table->index(['member_id', 'achieved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_ranks');
    }
};
