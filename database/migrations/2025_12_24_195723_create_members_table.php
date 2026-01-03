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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dojo_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'leave', 'inactive'])->default('active');
            $table->date('join_date')->nullable();
            $table->string('current_level')->nullable();
            $table->unsignedBigInteger('current_belt_id')->nullable(); // Foreign key to ranks table
            $table->string('style')->nullable();
            $table->text('medical_notes')->nullable();
            $table->timestamp('waiver_signed_at')->nullable();
            $table->string('waiver_document_path')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamp('qr_code_expires_at')->nullable();
            $table->string('profile_photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('dojo_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
