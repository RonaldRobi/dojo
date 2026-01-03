<?php

namespace App\Services;

use App\Models\Dojo;
use App\Models\Member;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    public function getRetentionRate(Dojo $dojo, $period = 'month'): array
    {
        $startDate = $period === 'month' 
            ? Carbon::now()->subMonths(12) 
            : Carbon::now()->subYears(2);

        $members = Member::where('dojo_id', $dojo->id)
            ->where('join_date', '>=', $startDate)
            ->get();

        $activeCount = $members->where('status', 'active')->count();
        $totalCount = $members->count();

        $retentionRate = $totalCount > 0 
            ? ($activeCount / $totalCount) * 100 
            : 0;

        return [
            'total_members' => $totalCount,
            'active_members' => $activeCount,
            'retention_rate' => round($retentionRate, 2),
            'period' => $period,
        ];
    }

    public function getRevenueReport(Dojo $dojo, $startDate, $endDate): array
    {
        $revenue = Payment::whereHas('invoice', function($q) use ($dojo) {
            $q->where('dojo_id', $dojo->id);
        })
        ->whereBetween('payment_date', [$startDate, $endDate])
        ->sum('amount');

        $invoices = Invoice::where('dojo_id', $dojo->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalInvoiced = $invoices->sum('total_amount');
        $paidInvoices = $invoices->where('status', 'paid')->count();
        $pendingInvoices = $invoices->where('status', 'pending')->count();
        $overdueInvoices = $invoices->where('status', 'overdue')->count();

        return [
            'total_revenue' => $revenue,
            'total_invoiced' => $totalInvoiced,
            'paid_count' => $paidInvoices,
            'pending_count' => $pendingInvoices,
            'overdue_count' => $overdueInvoices,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];
    }

    public function getAttendanceReport(Dojo $dojo, $startDate, $endDate): array
    {
        $attendances = Attendance::whereHas('member', function($q) use ($dojo) {
            $q->where('dojo_id', $dojo->id);
        })
        ->whereBetween('attendance_date', [$startDate, $endDate])
        ->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $excusedCount = $attendances->where('status', 'excused')->count();
        $totalCount = $attendances->count();

        return [
            'total' => $totalCount,
            'present' => $presentCount,
            'absent' => $absentCount,
            'excused' => $excusedCount,
            'attendance_rate' => $totalCount > 0 
                ? round(($presentCount / $totalCount) * 100, 2) 
                : 0,
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
        ];
    }

    public function getTopClasses(Dojo $dojo, $limit = 10): array
    {
        $classes = DB::table('class_enrollments')
            ->join('class_schedules', 'class_enrollments.class_schedule_id', '=', 'class_schedules.id')
            ->join('classes', 'class_schedules.class_id', '=', 'classes.id')
            ->where('classes.dojo_id', $dojo->id)
            ->where('class_enrollments.status', 'active')
            ->select('classes.id', 'classes.name', DB::raw('COUNT(class_enrollments.id) as enrollment_count'))
            ->groupBy('classes.id', 'classes.name')
            ->orderBy('enrollment_count', 'desc')
            ->limit($limit)
            ->get();

        return $classes->toArray();
    }
}

