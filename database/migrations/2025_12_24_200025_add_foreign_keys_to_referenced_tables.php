<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add foreign key for member_attendances.class_schedule_id
        Schema::table('member_attendances', function (Blueprint $table) {
            $table->foreign('class_schedule_id')->references('id')->on('class_schedules')->onDelete('cascade');
        });

        // Add foreign key for member_class_history.class_id
        Schema::table('member_class_history', function (Blueprint $table) {
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });

        // Add foreign key for member_instructor_history.instructor_id
        Schema::table('member_instructor_history', function (Blueprint $table) {
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
        });

        // Add foreign key for class_schedules.instructor_id
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('set null');
        });

        // Add foreign key for members.current_belt_id
        Schema::table('members', function (Blueprint $table) {
            $table->foreign('current_belt_id')->references('id')->on('ranks')->onDelete('set null');
        });

        // Add foreign key for member_ranks.grading_event_id
        Schema::table('member_ranks', function (Blueprint $table) {
            $table->foreign('grading_event_id')->references('id')->on('events')->onDelete('set null');
        });

        // Add foreign key for event_registrations.payment_invoice_id
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->foreign('payment_invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('member_attendances', function (Blueprint $table) {
            $table->dropForeign(['class_schedule_id']);
        });

        Schema::table('member_class_history', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
        });

        Schema::table('member_instructor_history', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
        });

        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropForeign(['instructor_id']);
        });

        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['current_belt_id']);
        });

        Schema::table('member_ranks', function (Blueprint $table) {
            $table->dropForeign(['grading_event_id']);
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropForeign(['payment_invoice_id']);
        });
    }
};
