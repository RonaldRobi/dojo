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
        Schema::table('invoices', function (Blueprint $table) {
            // Add payment gateway related columns
            if (!Schema::hasColumn('invoices', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('invoices', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('invoices', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_gateway');
            }
            
            if (!Schema::hasColumn('invoices', 'payment_url')) {
                $table->text('payment_url')->nullable()->after('payment_reference');
            }
            
            if (!Schema::hasColumn('invoices', 'description')) {
                $table->text('description')->nullable()->after('total_amount');
            }
            
            if (!Schema::hasColumn('invoices', 'invoice_date')) {
                $table->date('invoice_date')->nullable()->after('invoice_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $columns = ['payment_method', 'payment_gateway', 'payment_reference', 'payment_url', 'description', 'invoice_date'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('invoices', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
