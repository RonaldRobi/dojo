<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to varchar to be more flexible
        DB::statement("ALTER TABLE `member_attendances` MODIFY `checked_in_method` VARCHAR(50) NULL");
    }

    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE `member_attendances` MODIFY `checked_in_method` ENUM('qr', 'manual') NULL");
    }
};
