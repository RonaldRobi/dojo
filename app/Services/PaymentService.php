<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function processPayment(Invoice $invoice, array $data, ?User $verifiedBy = null): Payment
    {
        return DB::transaction(function () use ($invoice, $data, $verifiedBy) {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'reference_number' => $data['reference_number'] ?? null,
                'verified_by_user_id' => $verifiedBy?->id,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update invoice status if fully paid
            $totalPaid = $invoice->payments()->sum('amount');
            if ($totalPaid >= $invoice->total_amount) {
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }

            return $payment;
        });
    }

    public function verifyPayment(Payment $payment, User $verifiedBy): Payment
    {
        $payment->update([
            'verified_by_user_id' => $verifiedBy->id,
        ]);

        return $payment->fresh();
    }
}

