<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dojo_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('type', ['grading', 'sparring', 'tournament', 'seminar', 'workshop']);
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->date('registration_deadline')->nullable();
            $table->string('location')->nullable();
            $table->integer('capacity')->nullable();
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->string('event_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['dojo_id', 'event_date']);
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
