<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Payment::with(['invoice.member.user'])
            ->whereHas('invoice.member', function ($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            });

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function($iq) use ($search) {
                      $iq->where('invoice_number', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('owner.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        // Ensure payment belongs to owner's dojo
        if ($payment->invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        $payment->load(['invoice.member.user']);

        return view('owner.payments.show', compact('payment'));
    }

    public function create()
    {
        $dojoId = currentDojo();
        
        // Get pending invoices for this dojo
        $invoices = Invoice::with(['member.user'])
            ->whereHas('member', function ($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            })
            ->where('payment_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $dojoId = currentDojo();
        
        $validated = $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id', function($attribute, $value, $fail) use ($dojoId) {
                $invoice = Invoice::find($value);
                if ($invoice->member->dojo_id !== $dojoId) {
                    $fail('The selected invoice does not belong to your dojo.');
                }
                if ($invoice->payment_status !== 'pending') {
                    $fail('This invoice has already been paid.');
                }
            }],
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bank_transfer,online,fpx,bayar_cash',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        $payment = Payment::create([
            'invoice_id' => $validated['invoice_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'] ?? 'PAY-' . strtoupper(uniqid()),
            'status' => 'completed',
            'paid_at' => now(),
            'notes' => $validated['notes'],
        ]);

        // Update invoice status
        $invoice->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('owner.payments.show', $payment)
            ->with('success', 'Payment recorded successfully.');
    }

    public function edit(Payment $payment)
    {
        // Ensure payment belongs to owner's dojo
        if ($payment->invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        return view('owner.payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        // Ensure payment belongs to owner's dojo
        if ($payment->invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('owner.payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        // Ensure payment belongs to owner's dojo
        if ($payment->invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        // Update invoice back to pending
        $payment->invoice->update([
            'payment_status' => 'pending',
            'paid_at' => null,
        ]);

        $payment->delete();

        return redirect()->route('owner.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
