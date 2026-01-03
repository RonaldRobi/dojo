<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Payment::whereHas('invoice', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with(['invoice.member']);

        if ($request->has('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('verified_by_user_id');
            } else {
                $query->whereNull('verified_by_user_id');
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_reference', 'like', "%{$search}%")
                  ->orWhereHas('invoice.member', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $payments = $query->latest()->paginate(20);

        return view('finance.payments.index', compact('payments'));
    }

    public function create()
    {
        $dojoId = currentDojo();
        $invoices = Invoice::where('dojo_id', $dojoId)
            ->where('status', '!=', 'paid')
            ->with('member')
            ->get();
        return view('finance.payments.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'payment_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        $payment = $this->paymentService->recordPayment($invoice, $validated);

        return redirect()->route('finance.payments.show', $payment)
            ->with('success', 'Payment recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice.member', 'verifiedBy']);
        return view('finance.payments.show', compact('payment'));
    }

    public function verify(Payment $payment)
    {
        $this->paymentService->verifyPayment($payment, auth()->user());
        return redirect()->back()->with('success', 'Payment verified successfully.');
    }
}
