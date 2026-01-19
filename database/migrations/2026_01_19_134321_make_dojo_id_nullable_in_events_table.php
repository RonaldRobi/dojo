<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop the existing foreign key first
            $table->dropForeign(['dojo_id']);
            
            // Make the column nullable
            $table->foreignId('dojo_id')->nullable()->change();
            
            // Re-add the foreign key with nullable
            $table->foreign('dojo_id')->references('id')->on('dojos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Drop the foreign key
            $table->dropForeign(['dojo_id']);
            
            // Revert to non-nullable
            $table->foreignId('dojo_id')->nullable(false)->change();
            
            // Re-add the foreign key
            $table->foreign('dojo_id')->references('id')->on('dojos')->onDelete('cascade');
        });
    }
};
