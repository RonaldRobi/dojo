<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Dojo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceManagementController extends Controller
{
    // Monitoring Pembayaran Global
    public function payments(Request $request)
    {
        $query = Payment::with(['invoice.member.dojo', 'invoice.dojo']);

        if ($request->has('dojo_id')) {
            $query->whereHas('invoice', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        // Statistics
        $stats = [
            'total_amount' => Payment::sum('amount'),
            'today_amount' => Payment::whereDate('payment_date', today())->sum('amount'),
            'month_amount' => Payment::whereMonth('payment_date', Carbon::now()->month)
                ->whereYear('payment_date', Carbon::now()->year)
                ->sum('amount'),
        ];

        return view('admin.finance.payments', compact('payments', 'dojos', 'stats'));
    }

    // Laporan Revenue Semua Cabang
    public function revenueAll(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $dojos = Dojo::with(['members', 'invoices'])->get();
        $revenues = $dojos->map(function($dojo) use ($dateFrom, $dateTo) {
            $revenue = Payment::whereHas('invoice', function($q) use ($dojo) {
                $q->where('dojo_id', $dojo->id);
            })
            ->whereBetween('payment_date', [$dateFrom, $dateTo])
            ->sum('amount');

            return [
                'id' => $dojo->id,
                'name' => $dojo->name,
                'total_revenue' => $revenue,
                'members_count' => $dojo->members->count(),
                'invoices_count' => $dojo->invoices->count(),
            ];
        })->sortByDesc('total_revenue')->values();

        $totalRevenue = $revenues->sum('total_revenue');

        return view('admin.finance.revenue-all', compact('revenues', 'totalRevenue', 'dateFrom', 'dateTo'));
    }

    // Rekap Tunggakan
    public function arrears(Request $request)
    {
        $query = Invoice::with(['dojo', 'member'])
            ->whereIn('status', ['pending', 'overdue']);

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('due_date', 'asc')->paginate(20);
        $dojos = Dojo::all();

        $stats = [
            'total_pending' => Invoice::where('status', 'pending')->sum('total_amount'),
            'total_overdue' => Invoice::where('status', 'overdue')->sum('total_amount'),
            'count_pending' => Invoice::where('status', 'pending')->count(),
            'count_overdue' => Invoice::where('status', 'overdue')->count(),
        ];

        return view('admin.finance.arrears', compact('invoices', 'dojos', 'stats'));
    }

    // Laporan Cashflow Global
    public function cashflow(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // Daily cashflow
        $dailyCashflow = Payment::select(
            DB::raw('DATE(payment_date) as date'),
            DB::raw('SUM(amount) as total')
        )
        ->whereBetween('payment_date', [$dateFrom, $dateTo])
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        // By payment method
        $byMethod = Payment::select(
            'payment_method',
            DB::raw('SUM(amount) as total'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('payment_date', [$dateFrom, $dateTo])
        ->groupBy('payment_method')
        ->get();

        $totalCashflow = $dailyCashflow->sum('total');

        return view('admin.finance.cashflow', compact('dailyCashflow', 'byMethod', 'totalCashflow', 'dateFrom', 'dateTo'));
    }
}
