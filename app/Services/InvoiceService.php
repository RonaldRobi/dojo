<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Member;
use App\Models\Dojo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceService
{
    public function generateInvoice(array $data, Member $member, Dojo $dojo): Invoice
    {
        return DB::transaction(function () use ($data, $member, $dojo) {
            $invoiceNumber = $this->generateInvoiceNumber($dojo);

            $invoice = Invoice::create([
                'dojo_id' => $dojo->id,
                'member_id' => $member->id,
                'invoice_number' => $invoiceNumber,
                'type' => $data['type'],
                'amount' => $data['amount'] ?? 0,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'tax_amount' => $data['tax_amount'] ?? 0,
                'total_amount' => ($data['amount'] ?? 0) - ($data['discount_amount'] ?? 0) + ($data['tax_amount'] ?? 0),
                'due_date' => $data['due_date'] ?? now()->addDays(30),
                'status' => $data['status'] ?? 'pending',
            ]);

            return $invoice;
        });
    }

    protected function generateInvoiceNumber(Dojo $dojo): string
    {
        $prefix = 'INV-' . strtoupper(substr($dojo->name, 0, 3)) . '-';
        $year = now()->year;
        $month = now()->format('m');
        
        $lastInvoice = Invoice::where('dojo_id', $dojo->id)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastInvoice ? ((int) substr($lastInvoice->invoice_number, -4)) + 1 : 1;

        return $prefix . $year . $month . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function calculateTotal(Invoice $invoice): float
    {
        $itemsTotal = $invoice->items()->sum('total_price');
        $total = $itemsTotal - $invoice->discount_amount + $invoice->tax_amount;
        
        $invoice->update([
            'amount' => $itemsTotal,
            'total_amount' => $total,
        ]);

        return $total;
    }
}

