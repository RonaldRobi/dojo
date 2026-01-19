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
    // Monitoring Pembayaran Global (dari Invoices)
    public function payments(Request $request)
    {
        // Ambil data dari tabel invoices
        $query = Invoice::with(['member.user', 'dojo', 'payments']);

        // Filter by dojo
        if ($request->has('dojo_id') && $request->dojo_id != '') {
            $query->where('dojo_id', $request->dojo_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by invoice type
        if ($request->has('invoice_type') && $request->invoice_type != '') {
            $query->where('invoice_type', $request->invoice_type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // Get invoices (payments)
        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        // Statistics dari Invoices
        $stats = [
            'total_invoices' => Invoice::count(),
            'total_amount' => Invoice::sum('total_amount'),
            'paid_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
            'pending_amount' => Invoice::where('status', 'pending')->sum('total_amount'),
            'overdue_amount' => Invoice::where('status', 'overdue')->sum('total_amount'),
            'cancelled_amount' => Invoice::where('status', 'cancelled')->sum('total_amount'),
            'today_invoices' => Invoice::whereDate('invoice_date', today())->count(),
            'today_amount' => Invoice::whereDate('invoice_date', today())->sum('total_amount'),
            'month_amount' => Invoice::whereMonth('invoice_date', Carbon::now()->month)
                ->whereYear('invoice_date', Carbon::now()->year)
                ->sum('total_amount'),
            'paid_count' => Invoice::where('status', 'paid')->count(),
            'pending_count' => Invoice::where('status', 'pending')->count(),
            'overdue_count' => Invoice::where('status', 'overdue')->count(),
        ];

        return view('admin.finance.payments', compact('invoices', 'dojos', 'stats'));
    }

    // Laporan Revenue Semua Cabang (dari Invoices yang Paid)
    public function revenueAll(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $dojos = Dojo::with(['members', 'invoices'])->get();
        $revenues = $dojos->map(function($dojo) use ($dateFrom, $dateTo) {
            // Revenue dari Invoices yang PAID saja
            // Include invoices dengan invoice_date NULL (gunakan created_at sebagai fallback)
            $revenue = Invoice::where('dojo_id', $dojo->id)
                ->where('status', 'paid')
                ->where(function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('invoice_date', [$dateFrom, $dateTo])
                      ->orWhere(function($q2) use ($dateFrom, $dateTo) {
                          $q2->whereNull('invoice_date')
                             ->whereBetween('created_at', [$dateFrom, $dateTo]);
                      });
                })
                ->sum('total_amount');

            // Total invoices (all statuses)
            $totalInvoices = Invoice::where('dojo_id', $dojo->id)
                ->where(function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('invoice_date', [$dateFrom, $dateTo])
                      ->orWhere(function($q2) use ($dateFrom, $dateTo) {
                          $q2->whereNull('invoice_date')
                             ->whereBetween('created_at', [$dateFrom, $dateTo]);
                      });
                })
                ->count();

            // Paid invoices count
            $paidInvoices = Invoice::where('dojo_id', $dojo->id)
                ->where('status', 'paid')
                ->where(function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('invoice_date', [$dateFrom, $dateTo])
                      ->orWhere(function($q2) use ($dateFrom, $dateTo) {
                          $q2->whereNull('invoice_date')
                             ->whereBetween('created_at', [$dateFrom, $dateTo]);
                      });
                })
                ->count();

            return [
                'id' => $dojo->id,
                'name' => $dojo->name,
                'total_revenue' => $revenue,
                'members_count' => $dojo->members->count(),
                'invoices_count' => $totalInvoices,
                'paid_invoices' => $paidInvoices,
            ];
        })->sortByDesc('total_revenue')->values();

        $totalRevenue = $revenues->sum('total_revenue');
        $totalInvoices = $revenues->sum('invoices_count');
        $totalPaidInvoices = $revenues->sum('paid_invoices');

        return view('admin.finance.revenue-all', compact('revenues', 'totalRevenue', 'totalInvoices', 'totalPaidInvoices', 'dateFrom', 'dateTo'));
    }

    // Rekap Tunggakan (dari Invoices)
    public function arrears(Request $request)
    {
        $query = Invoice::with(['dojo', 'member.user'])
            ->whereIn('status', ['pending', 'overdue']);

        // Filter by dojo
        if ($request->has('dojo_id') && $request->dojo_id != '') {
            $query->where('dojo_id', $request->dojo_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Order by due_date (with fallback to invoice_date or created_at for NULL)
        $invoices = $query->orderByRaw('COALESCE(due_date, invoice_date, created_at) ASC')
            ->paginate(20);
        
        $dojos = Dojo::all();

        // Stats dari Invoices
        $stats = [
            'total_pending' => Invoice::where('status', 'pending')->sum('total_amount'),
            'total_overdue' => Invoice::where('status', 'overdue')->sum('total_amount'),
            'count_pending' => Invoice::where('status', 'pending')->count(),
            'count_overdue' => Invoice::where('status', 'overdue')->count(),
            'total_arrears' => Invoice::whereIn('status', ['pending', 'overdue'])->sum('total_amount'),
            'count_arrears' => Invoice::whereIn('status', ['pending', 'overdue'])->count(),
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
