<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_registration_id')->constrained()->onDelete('cascade');
            $table->string('certificate_url');
            $table->timestamp('issued_at')->useCurrent();
            $table->foreignId('issued_by_instructor_id')->nullable()->constrained('instructors')->onDelete('set null');
            $table->string('certificate_type')->nullable();
            $table->timestamps();
            
            $table->index('event_registration_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_certificates');
    }
};
