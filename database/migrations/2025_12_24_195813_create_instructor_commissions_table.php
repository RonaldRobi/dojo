<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('base_rate', 10, 2)->nullable();
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('paid_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['instructor_id', 'period_start', 'period_end'], 'instructor_commissions_period_idx');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_commissions');
    }
};
