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
        Schema::table('class_schedules', function (Blueprint $table) {
            // Add new fields for simple weekly schedule
            $table->foreignId('dojo_id')->after('id')->constrained()->onDelete('cascade');
            $table->string('class_name')->after('dojo_id'); // e.g., "Advanced Sparring"
            $table->string('class_type')->nullable()->after('class_name'); // e.g., "Beginner", "Intermediate", "Advanced"
            
            // Make class_id nullable (we won't use it anymore)
            $table->unsignedBigInteger('class_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropForeign(['dojo_id']);
            $table->dropColumn(['dojo_id', 'class_name', 'class_type']);
            $table->unsignedBigInteger('class_id')->nullable(false)->change();
        });
    }
};
