<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $dojoId = currentDojo();
        $dojo = \App\Models\Dojo::find($dojoId);

        $stats = [
            'pending_payments' => Payment::whereHas('invoice', function($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            })->whereNull('verified_by_user_id')->count(),
            'overdue_invoices' => Invoice::where('dojo_id', $dojoId)->where('status', 'overdue')->count(),
            'today_revenue' => Payment::whereHas('invoice', function($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            })->whereDate('payment_date', today())->sum('amount'),
            'monthly_revenue' => $this->analyticsService->getRevenueReport(
                $dojo,
                now()->startOfMonth(),
                now()->endOfMonth()
            ),
            'total_pending' => Invoice::where('dojo_id', $dojoId)->where('status', 'pending')->sum('total_amount'),
            'total_overdue' => Invoice::where('dojo_id', $dojoId)->where('status', 'overdue')->sum('total_amount'),
        ];

        $recentPayments = Payment::whereHas('invoice', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })
        ->with(['invoice.member'])
        ->latest()
        ->limit(10)
        ->get();

        $recentInvoices = Invoice::where('dojo_id', $dojoId)
            ->with('member')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.finance', compact('stats', 'recentPayments', 'recentInvoices'));
    }
}
