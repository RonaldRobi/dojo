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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('dojo_id')->nullable()->after('id')->constrained()->onDelete('set null');
            $table->enum('status', ['active', 'suspended'])->default('active')->after('email_verified_at');
            $table->timestamp('password_changed_at')->nullable()->after('password');
            $table->timestamp('last_login_at')->nullable()->after('password_changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dojo_id']);
            $table->dropColumn(['dojo_id', 'status', 'last_login_at', 'password_changed_at']);
        });
    }
};
