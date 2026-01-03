<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent'])->default('present');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
            
            $table->index('event_registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_attendances');
    }
};
