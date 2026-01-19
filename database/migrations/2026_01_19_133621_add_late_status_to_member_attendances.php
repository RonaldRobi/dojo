<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update enum to include 'late' status
        DB::statement("ALTER TABLE `member_attendances` MODIFY `status` ENUM('present', 'late', 'absent', 'excused') NOT NULL DEFAULT 'present'");
    }

    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE `member_attendances` MODIFY `status` ENUM('present', 'absent', 'excused') NOT NULL DEFAULT 'present'");
    }
};
