<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->string('certification_name');
            $table->string('issued_by')->nullable();
            $table->date('issued_date');
            $table->date('expiry_date')->nullable();
            $table->string('certificate_document_path')->nullable();
            $table->timestamps();
            
            $table->index(['instructor_id', 'expiry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_certifications');
    }
};
