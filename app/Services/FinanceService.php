<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Member;
use App\Models\Membership;
use Carbon\Carbon;

class FinanceService
{
    public function generateAutoInvoice(Member $member, Membership $membership): Invoice
    {
        $invoiceService = app(InvoiceService::class);
        $dojo = $member->dojo;

        return $invoiceService->generateInvoice([
            'type' => 'membership',
            'amount' => $membership->price,
            'due_date' => now()->addDays(7),
            'status' => 'pending',
        ], $member, $dojo);
    }

    public function checkOverdueInvoices(): void
    {
        Invoice::where('status', 'pending')
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }

    public function calculateTotalRevenue(int $dojoId, $startDate, $endDate): float
    {
        return \App\Models\Payment::whereHas('invoice', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })
        ->whereBetween('payment_date', [$startDate, $endDate])
        ->sum('amount');
    }
}

