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
            // Make these columns nullable since we're not using them anymore
            $table->string('class_name')->nullable()->change();
            $table->string('class_type')->nullable()->change();
            $table->string('location')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->string('class_name')->nullable(false)->change();
            $table->string('class_type')->nullable(false)->change();
            $table->string('location')->nullable(false)->change();
        });
    }
};
