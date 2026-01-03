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
        Schema::create('parent_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('member_id'); // Foreign key will be added when members table is created
            $table->foreignId('dojo_id')->constrained()->onDelete('cascade');
            $table->foreignId('linked_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('linked_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['parent_user_id', 'member_id', 'dojo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_students');
    }
};
