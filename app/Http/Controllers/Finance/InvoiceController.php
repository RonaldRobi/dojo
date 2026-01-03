<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Member;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Invoice::where('dojo_id', $dojoId)
            ->with(['member', 'items', 'payments']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
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

        $invoices = $query->paginate(20);
        $members = Member::where('dojo_id', $dojoId)->get();

        return view('finance.invoices.index', compact('invoices', 'members'));
    }

    public function create()
    {
        $dojoId = currentDojo();
        $members = Member::where('dojo_id', $dojoId)->get();
        return view('finance.invoices.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'type' => 'required|in:membership,class,event,private',
            'amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'nullable|in:draft,pending,paid,overdue,cancelled',
        ]);

        $member = Member::findOrFail($validated['member_id']);
        $dojo = $member->dojo;

        $invoice = $this->invoiceService->generateInvoice($validated, $member, $dojo);

        return redirect()->route('finance.invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['member', 'items', 'payments']);
        return view('finance.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $dojoId = currentDojo();
        $members = Member::where('dojo_id', $dojoId)->get();
        return view('finance.invoices.edit', compact('invoice', 'members'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:draft,pending,paid,overdue,cancelled',
            'due_date' => 'sometimes|date',
        ]);

        $invoice = $this->invoiceService->updateInvoice($invoice, $validated);

        return redirect()->route('finance.invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $this->invoiceService->deleteInvoice($invoice);
        return redirect()->route('finance.invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}
