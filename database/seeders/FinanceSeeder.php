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

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        foreach ($dojos as $dojo) {
            $members = Member::where('dojo_id', $dojo->id)->get();
            $memberships = Membership::where('dojo_id', $dojo->id)->where('is_active', true)->get();
            $financeUsers = User::whereHas('roles', function($q) use ($dojo) {
                $q->where('name', 'finance')->where('dojo_id', $dojo->id);
            })->get();

            $financeUser = $financeUsers->first();

            if ($members->isEmpty() || $memberships->isEmpty()) {
                continue;
            }

            $monthlyMembership = $memberships->where('duration_days', 30)->first();

            // Generate invoices for the last 6 months
            for ($monthOffset = 0; $monthOffset < 6; $monthOffset++) {
                $invoiceDate = Carbon::now()->subMonths($monthOffset)->startOfMonth();
                
                foreach ($members as $member) {
                    // Skip some members randomly (not everyone gets invoice every month)
                    if (rand(1, 10) > 7) {
                        continue;
                    }

                    $invoiceNumber = 'INV-' . $dojo->id . '-' . $invoiceDate->format('Ym') . '-' . str_pad($member->id, 4, '0', STR_PAD_LEFT);
                    
                    $amount = $monthlyMembership ? $monthlyMembership->price : 500000;
                    $discountAmount = rand(0, 1) ? rand(0, 50000) : 0;
                    $taxAmount = ($amount - $discountAmount) * 0.11; // 11% tax
                    $totalAmount = $amount - $discountAmount + $taxAmount;
                    $dueDate = $invoiceDate->copy()->addDays(7);

                    $statuses = ['paid', 'paid', 'paid', 'pending', 'overdue'];
                    $status = $statuses[rand(0, count($statuses) - 1)];

                    $invoice = Invoice::updateOrCreate(
                        [
                            'dojo_id' => $dojo->id,
                            'invoice_number' => $invoiceNumber,
                        ],
                        [
                            'member_id' => $member->id,
                            'type' => 'membership',
                            'amount' => $amount,
                            'discount_amount' => $discountAmount,
                            'tax_amount' => $taxAmount,
                            'total_amount' => $totalAmount,
                            'due_date' => $dueDate,
                            'status' => $status,
                            'paid_at' => $status === 'paid' ? $invoiceDate->copy()->addDays(rand(1, 5)) : null,
                        ]
                    );

                    // Add invoice items
                    InvoiceItem::updateOrCreate(
                        [
                            'invoice_id' => $invoice->id,
                            'description' => 'Monthly Membership Fee',
                        ],
                        [
                            'quantity' => 1,
                            'unit_price' => $amount,
                            'total_price' => $amount,
                        ]
                    );

                    if ($discountAmount > 0) {
                        InvoiceItem::updateOrCreate(
                            [
                                'invoice_id' => $invoice->id,
                                'description' => 'Early Payment Discount',
                            ],
                            [
                                'quantity' => 1,
                                'unit_price' => -$discountAmount,
                                'total_price' => -$discountAmount,
                            ]
                        );
                    }

                    // Add payment if invoice is paid
                    if ($status === 'paid') {
                        $paymentMethods = ['cash', 'transfer', 'credit_card'];
                        $paymentMethod = $paymentMethods[rand(0, count($paymentMethods) - 1)];
                        
                        Payment::updateOrCreate(
                            [
                                'invoice_id' => $invoice->id,
                                'payment_date' => $invoice->paid_at->format('Y-m-d'),
                            ],
                            [
                                'amount' => $totalAmount,
                                'payment_method' => $paymentMethod,
                                'reference_number' => 'REF-' . strtoupper(str()->random(10)),
                                'verified_by_user_id' => $financeUser ? $financeUser->id : null,
                                'notes' => 'Payment received via ' . $paymentMethod,
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('Invoices and payments seeded successfully!');
    }
}

