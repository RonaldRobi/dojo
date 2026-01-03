<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->integer('position');
            $table->timestamp('added_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['member_id', 'class_schedule_id']);
            $table->index(['class_schedule_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_waitlists');
    }
};
