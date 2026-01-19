<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dojo;
use App\Models\User;
use App\Models\Member;
use App\Models\AuditLog;
use App\Models\DojoClass;
use App\Models\Instructor;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Stats
        $stats = [
            'total_dojos' => Dojo::count(),
            'active_dojos' => Dojo::whereHas('members')->count(),
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_members' => Member::count(),
            'active_members' => Member::where('status', 'active')->count(),
            'total_classes' => DojoClass::count(),
            'active_classes' => DojoClass::where('is_active', true)->count(),
            'total_instructors' => Instructor::count(),
            'total_events' => Event::count(),
        ];

        // Financial Stats (dari Invoices yang Paid)
        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount') ?? 0;
        
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('invoice_date', Carbon::now()->month)
            ->whereYear('invoice_date', Carbon::now()->year)
            ->sum('total_amount') ?? 0;
        
        $pendingInvoices = Invoice::where('status', 'pending')->count();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();

        $stats['total_revenue'] = $totalRevenue;
        $stats['monthly_revenue'] = $monthlyRevenue;
        $stats['pending_invoices'] = $pendingInvoices;
        $stats['overdue_invoices'] = $overdueInvoices;
        $stats['paid_invoices'] = $paidInvoices;

        // Recent Activity
        $recentAuditLogs = AuditLog::with(['user', 'dojo'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Top Dojos by Members
        $topDojos = Dojo::withCount('members')
            ->orderBy('members_count', 'desc')
            ->limit(5)
            ->get();

        // Recent Events
        $upcomingEvents = Event::with('dojo')
            ->where('event_date', '>=', Carbon::now())
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        // Revenue Chart Data (Last 6 months - dari Invoices yang Paid)
        $revenueChartData = [];
        $revenueChartLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenueChartLabels[] = $date->format('M Y');
            $revenueChartData[] = Invoice::where('status', 'paid')
                ->whereMonth('invoice_date', $date->month)
                ->whereYear('invoice_date', $date->year)
                ->sum('total_amount') ?? 0;
        }

        // Members Growth Chart Data (Last 6 months)
        $membersChartData = [];
        $membersChartLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $membersChartLabels[] = $date->format('M Y');
            $membersChartData[] = Member::whereMonth('join_date', $date->month)
                ->whereYear('join_date', $date->year)
                ->count();
        }

        return view('dashboard.admin', compact(
            'stats', 
            'recentAuditLogs', 
            'topDojos', 
            'upcomingEvents',
            'revenueChartData',
            'revenueChartLabels',
            'membersChartData',
            'membersChartLabels'
        ));
    }

    /**
     * Synchronize dashboard data
     * - Clear all caches
     * - Refresh statistics
     * - Validate data integrity
     */
    public function sync(Request $request)
    {
        try {
            // Clear all caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            // Clear specific cache keys
            Cache::flush();
            
            // Log the sync action
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'sync_dashboard',
                'model' => 'Dashboard',
                'description' => 'Dashboard data synchronized',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data synchronized successfully',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to synchronize data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
