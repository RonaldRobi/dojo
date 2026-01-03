<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('member_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('class_schedule_id'); // Foreign key will be added when class_schedules table is created
            $table->date('attendance_date');
            $table->enum('status', ['present', 'absent', 'excused'])->default('present');
            $table->timestamp('checked_in_at')->nullable();
            $table->enum('checked_in_method', ['qr', 'manual'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['member_id', 'attendance_date']);
            $table->index('class_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_attendances');
    }
};
