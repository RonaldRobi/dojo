<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Member;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Invoice::with(['member.user'])
            ->whereHas('member', function ($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            });

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('member', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('owner.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        // Ensure invoice belongs to owner's dojo
        if ($invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        $invoice->load(['member.user', 'items']);

        return view('owner.invoices.show', compact('invoice'));
    }

    public function create()
    {
        $dojoId = currentDojo();
        $members = Member::where('dojo_id', $dojoId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('owner.invoices.create', compact('members'));
    }

    public function store(Request $request)
    {
        $dojoId = currentDojo();
        
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id', function($attribute, $value, $fail) use ($dojoId) {
                $member = Member::find($value);
                if ($member->dojo_id !== $dojoId) {
                    $fail('The selected member does not belong to your dojo.');
                }
            }],
            'type' => 'required|in:membership,class,event,private',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            'dojo_id' => $dojoId,
            'member_id' => $validated['member_id'],
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'total_amount' => $validated['amount'],
            'due_date' => $validated['due_date'] ?? now()->addDays(7),
            'status' => 'pending',
            'description' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('owner.invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function edit(Invoice $invoice)
    {
        // Ensure invoice belongs to owner's dojo
        if ($invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow editing if still pending
        if ($invoice->status !== 'pending') {
            return redirect()->route('owner.invoices.show', $invoice)
                ->with('error', 'Only pending invoices can be edited.');
        }

        $dojoId = currentDojo();
        $members = Member::where('dojo_id', $dojoId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('owner.invoices.edit', compact('invoice', 'members'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // Ensure invoice belongs to owner's dojo
        if ($invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow editing if still pending
        if ($invoice->status !== 'pending') {
            return redirect()->route('owner.invoices.show', $invoice)
                ->with('error', 'Only pending invoices can be edited.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($validated);

        return redirect()->route('owner.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        // Ensure invoice belongs to owner's dojo
        if ($invoice->member->dojo_id !== currentDojo()) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow deleting if still pending
        if ($invoice->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending invoices can be deleted.');
        }

        $invoice->delete();

        return redirect()->route('owner.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}
