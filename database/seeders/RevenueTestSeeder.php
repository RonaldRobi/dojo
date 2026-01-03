<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Dojo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueTestSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        // Clear existing finance data for testing
        $this->command->info('Clearing existing finance data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Payment::truncate();
        InvoiceItem::truncate();
        Invoice::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($dojos as $dojo) {
            $members = Member::where('dojo_id', $dojo->id)->get();
            $memberships = Membership::where('dojo_id', $dojo->id)->where('is_active', true)->get();
            $financeUsers = User::whereHas('roles', function($q) use ($dojo) {
                $q->where('name', 'finance')->where('dojo_id', $dojo->id);
            })->get();

            $financeUser = $financeUsers->first();
            $ownerUser = User::where('dojo_id', $dojo->id)->whereHas('roles', function($q) {
                $q->where('name', 'owner');
            })->first();

            // Create default membership if not exists
            if ($memberships->isEmpty()) {
                $this->command->info("Creating default membership for {$dojo->name}...");
                $monthlyMembership = Membership::create([
                    'dojo_id' => $dojo->id,
                    'name' => 'Monthly Membership',
                    'description' => 'Monthly membership fee',
                    'price' => 500000,
                    'duration_days' => 30,
                    'is_active' => true,
                ]);
            } else {
                $monthlyMembership = $memberships->where('duration_days', 30)->first() ?? $memberships->first();
            }

            // Create test members if not exists
            if ($members->isEmpty()) {
                $this->command->info("Creating test members for {$dojo->name}...");
                for ($i = 1; $i <= 20; $i++) {
                    $user = User::create([
                        'name' => "Test Member {$i} - {$dojo->name}",
                        'email' => "testmember{$i}_{$dojo->id}@dojo.com",
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'dojo_id' => $dojo->id,
                        'status' => 'active',
                        'password_changed_at' => now(),
                    ]);

                    Member::create([
                        'dojo_id' => $dojo->id,
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'birth_date' => Carbon::now()->subYears(rand(10, 40)),
                        'gender' => ['male', 'female'][rand(0, 1)],
                        'phone' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                        'status' => 'active',
                        'join_date' => Carbon::now()->subMonths(rand(1, 12)),
                        'style' => 'Karate',
                    ]);
                }
                $members = Member::where('dojo_id', $dojo->id)->get();
            }

            $this->command->info("Generating revenue test data for {$dojo->name}...");

            // Generate invoices and payments for the last 12 months (for chart testing)
            $totalInvoices = 0;
            $totalRevenue = 0;
            $invoiceCounter = 1;

            for ($monthOffset = 11; $monthOffset >= 0; $monthOffset--) {
                $invoiceMonth = Carbon::now()->subMonths($monthOffset);
                $invoiceDate = $invoiceMonth->copy()->startOfMonth();
                
                // Generate 20-30 invoices per month
                $invoicesPerMonth = rand(20, 30);
                
                for ($i = 0; $i < $invoicesPerMonth; $i++) {
                    // Random member
                    $member = $members->random();
                    
                    // Random invoice date within the month
                    $invoiceCreatedDate = $invoiceDate->copy()->addDays(rand(0, $invoiceDate->copy()->endOfMonth()->day - 1));
                    
                    $invoiceNumber = 'INV-' . $dojo->id . '-' . $invoiceCreatedDate->format('Ym') . '-' . str_pad($invoiceCounter++, 6, '0', STR_PAD_LEFT);
                    
                    // Base amount from membership or random
                    $amount = $monthlyMembership->price ?? rand(400000, 600000);
                    
                    // Random discount (10% chance)
                    $discountAmount = rand(1, 10) <= 1 ? rand(25000, 100000) : 0;
                    
                    // Tax calculation (11% VAT)
                    $taxAmount = ($amount - $discountAmount) * 0.11;
                    $totalAmount = $amount - $discountAmount + $taxAmount;
                    
                    // Due date: 7-14 days after invoice date
                    $dueDate = $invoiceCreatedDate->copy()->addDays(rand(7, 14));

                    // Status distribution: 70% paid, 20% pending, 10% overdue
                    $statusRand = rand(1, 10);
                    if ($statusRand <= 7) {
                        $status = 'paid';
                    } elseif ($statusRand <= 9) {
                        $status = 'pending';
                    } else {
                        $status = 'overdue';
                    }

                    $invoice = Invoice::create([
                        'dojo_id' => $dojo->id,
                        'member_id' => $member->id,
                        'invoice_number' => $invoiceNumber,
                        'type' => 'membership',
                        'amount' => $amount,
                        'discount_amount' => $discountAmount,
                        'tax_amount' => $taxAmount,
                        'total_amount' => $totalAmount,
                        'due_date' => $dueDate,
                        'status' => $status,
                        'paid_at' => $status === 'paid' ? $invoiceCreatedDate->copy()->addDays(rand(1, 5)) : null,
                        'created_at' => $invoiceCreatedDate,
                        'updated_at' => $invoiceCreatedDate,
                    ]);

                    // Add invoice items
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'description' => 'Monthly Membership Fee',
                        'quantity' => 1,
                        'unit_price' => $amount,
                        'total_price' => $amount,
                    ]);

                    if ($discountAmount > 0) {
                        InvoiceItem::create([
                            'invoice_id' => $invoice->id,
                            'description' => 'Early Payment Discount',
                            'quantity' => 1,
                            'unit_price' => -$discountAmount,
                            'total_price' => -$discountAmount,
                        ]);
                    }

                    // Add payment if invoice is paid
                    if ($status === 'paid') {
                        $paymentMethods = ['cash', 'transfer', 'credit_card', 'debit_card'];
                        $paymentMethod = $paymentMethods[rand(0, count($paymentMethods) - 1)];
                        
                        $paymentDate = $invoice->paid_at ?? $invoiceCreatedDate->copy()->addDays(rand(1, 5));
                        
                        $payment = Payment::create([
                            'invoice_id' => $invoice->id,
                            'amount' => $totalAmount,
                            'payment_method' => $paymentMethod,
                            'payment_date' => $paymentDate->format('Y-m-d'),
                            'reference_number' => 'REF-' . strtoupper(str()->random(10)),
                            'verified_by_user_id' => $financeUser ? $financeUser->id : ($ownerUser ? $ownerUser->id : null),
                            'notes' => 'Payment received via ' . $paymentMethod,
                            'created_at' => $paymentDate,
                            'updated_at' => $paymentDate,
                        ]);

                        $totalRevenue += $totalAmount;
                    }

                    $totalInvoices++;
                }
            }

            // Generate some additional invoices for current month (mixed status)
            $currentMonth = Carbon::now()->startOfMonth();
            $currentMonthInvoices = rand(15, 25);
            
            for ($i = 0; $i < $currentMonthInvoices; $i++) {
                $member = $members->random();
                $invoiceDate = $currentMonth->copy()->addDays(rand(0, Carbon::now()->day - 1));
                
                $invoiceNumber = 'INV-' . $dojo->id . '-' . $invoiceDate->format('Ym') . '-' . str_pad($invoiceCounter++, 6, '0', STR_PAD_LEFT);
                
                $amount = $monthlyMembership ? $monthlyMembership->price : rand(400000, 600000);
                $discountAmount = 0;
                $taxAmount = $amount * 0.11;
                $totalAmount = $amount + $taxAmount;
                $dueDate = $invoiceDate->copy()->addDays(7);

                $statusRand = rand(1, 10);
                $status = $statusRand <= 6 ? 'paid' : ($statusRand <= 9 ? 'pending' : 'overdue');

                $invoice = Invoice::create([
                    'dojo_id' => $dojo->id,
                    'member_id' => $member->id,
                    'invoice_number' => $invoiceNumber,
                    'type' => 'membership',
                    'amount' => $amount,
                    'discount_amount' => $discountAmount,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'paid_at' => $status === 'paid' ? $invoiceDate->copy()->addDays(rand(1, 3)) : null,
                    'created_at' => $invoiceDate,
                    'updated_at' => $invoiceDate,
                ]);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Monthly Membership Fee',
                    'quantity' => 1,
                    'unit_price' => $amount,
                    'total_price' => $amount,
                ]);

                if ($status === 'paid') {
                    $paymentDate = $invoice->paid_at ?? $invoiceDate->copy()->addDays(rand(1, 3));
                    
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount' => $totalAmount,
                        'payment_method' => ['cash', 'transfer'][rand(0, 1)],
                        'payment_date' => $paymentDate->format('Y-m-d'),
                        'reference_number' => 'REF-' . strtoupper(str()->random(10)),
                        'verified_by_user_id' => $financeUser ? $financeUser->id : ($ownerUser ? $ownerUser->id : null),
                        'notes' => 'Payment received',
                        'created_at' => $paymentDate,
                        'updated_at' => $paymentDate,
                    ]);

                    $totalRevenue += $totalAmount;
                }

                $totalInvoices++;
            }

            $this->command->info("Generated {$totalInvoices} invoices for {$dojo->name}");
            $this->command->info("Total revenue: RM " . number_format($totalRevenue, 2));
        }

        // Display summary
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        $totalRevenue = Payment::sum('amount');

        $this->command->info('');
        $this->command->info('=== Revenue Test Data Summary ===');
        $this->command->info("Total Invoices: {$totalInvoices}");
        $this->command->info("Paid Invoices: {$paidInvoices}");
        $this->command->info("Pending Invoices: {$pendingInvoices}");
        $this->command->info("Overdue Invoices: {$overdueInvoices}");
        $this->command->info("Total Revenue: RM " . number_format($totalRevenue, 2));
        $this->command->info('');
        $this->command->info('Revenue test data seeded successfully!');
    }
}

