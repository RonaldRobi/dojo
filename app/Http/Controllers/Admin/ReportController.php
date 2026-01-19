<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\DojoClass;
use App\Models\Instructor;
use App\Models\Event;
use App\Models\Dojo;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Retention Rate Global
    public function retention(Request $request, $dojo = null)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $queryDojos = $dojo ? Dojo::where('id', $dojo) : Dojo::all();
        $retentionData = [];

        foreach ($queryDojos as $dojoItem) {
            $newMembers = Member::where('dojo_id', $dojoItem->id)
                ->whereBetween('join_date', [$dateFrom, $dateTo])
                ->count();

            $activeMembers = Member::where('dojo_id', $dojoItem->id)
                ->where('status', 'active')
                ->count();

            $totalMembers = Member::where('dojo_id', $dojoItem->id)->count();

            $retentionRate = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 2) : 0;

            $retentionData[] = [
                'dojo' => $dojoItem->name,
                'new_members' => $newMembers,
                'active_members' => $activeMembers,
                'total_members' => $totalMembers,
                'retention_rate' => $retentionRate,
                ];
            }

        return view('admin.reports.retention', compact('retentionData', 'dateFrom', 'dateTo'));
        }

    // Laporan Kelas Terpopuler
    public function popularClasses(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $classes = DojoClass::with(['dojo', 'enrollments'])
            ->withCount(['enrollments' => function($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('class_enrollments.created_at', [$dateFrom, $dateTo]);
            }])
            ->orderBy('enrollments_count', 'desc')
            ->get();

        return view('admin.reports.popular-classes', compact('classes', 'dateFrom', 'dateTo'));
    }

    // Laporan Coach Teraktif
    public function activeCoaches(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        // Since ClassSchedule doesn't have schedule_date, count all active schedules
        $coaches = Instructor::with(['dojo'])
            ->withCount(['schedules' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('schedules_count', 'desc')
            ->get();

        return view('admin.reports.active-coaches', compact('coaches', 'dateFrom', 'dateTo'));
    }

    // Laporan Progres Siswa
    public function studentProgress(Request $request)
    {
        $query = Member::with(['dojo', 'ranks']);

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        $members = $query->withCount('ranks')
            ->orderBy('ranks_count', 'desc')
            ->paginate(20);

        $dojos = Dojo::all();

        return view('admin.reports.student-progress', compact('members', 'dojos'));
    }

    // Revenue Report
    public function revenue(Request $request)
    {
        // Default period is current month
        $period = $request->get('period', 'monthly');
        $dojoId = $request->get('dojo_id');
        $export = $request->get('export');

        // Calculate date range based on period
        switch ($period) {
            case 'weekly':
                $dateFrom = Carbon::now()->startOfWeek();
                $dateTo = Carbon::now()->endOfWeek();
                break;
            case 'yearly':
                $dateFrom = Carbon::now()->startOfYear();
                $dateTo = Carbon::now()->endOfYear();
                break;
            case 'monthly':
            default:
                $dateFrom = Carbon::now()->startOfMonth();
                $dateTo = Carbon::now()->endOfMonth();
                break;
        }

        // Allow custom date range if provided
        if ($request->has('date_from') && $request->has('date_to')) {
            $dateFrom = Carbon::parse($request->date_from);
            $dateTo = Carbon::parse($request->date_to);
        }

        // Build query
        $query = Invoice::with(['member.dojo'])
            ->where('status', 'paid')
            ->whereBetween(DB::raw('COALESCE(invoice_date, created_at)'), [$dateFrom, $dateTo]);

        if ($dojoId) {
            $query->whereHas('member', function($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            });
        }

        $invoices = $query->orderBy(DB::raw('COALESCE(invoice_date, created_at)'), 'desc')->get();

        // Calculate totals
        $totalRevenue = $invoices->sum('total_amount');
        $totalInvoices = $invoices->count();

        // Group by dojo
        $revenueByDojo = $invoices->groupBy(function($invoice) {
            return $invoice->member->dojo->name ?? 'Unknown';
        })->map(function($items, $dojo) {
            return [
                'dojo' => $dojo,
                'total' => $items->sum('total_amount'),
                'count' => $items->count(),
            ];
        });

        // Group by invoice type
        $revenueByType = $invoices->groupBy('invoice_type')->map(function($items, $type) {
            return [
                'type' => ucfirst(str_replace('_', ' ', $type)),
                'total' => $items->sum('total_amount'),
                'count' => $items->count(),
            ];
        });

        $dojos = Dojo::all();

        // Handle export
        if ($export === 'csv') {
            return $this->exportRevenueCSV($invoices, $dateFrom, $dateTo, $totalRevenue);
        } elseif ($export === 'pdf') {
            return $this->exportRevenuePDF($invoices, $dateFrom, $dateTo, $totalRevenue);
        }

        return view('admin.reports.revenue', compact(
            'invoices', 
            'totalRevenue', 
            'totalInvoices', 
            'revenueByDojo', 
            'revenueByType',
            'dateFrom', 
            'dateTo', 
            'period',
            'dojoId',
            'dojos'
        ));
    }

    // Event Report
    public function events(Request $request)
    {
        // Default period is current month
        $period = $request->get('period', 'monthly');
        $dojoId = $request->get('dojo_id');
        $export = $request->get('export');

        // Calculate date range based on period
        switch ($period) {
            case 'weekly':
                $dateFrom = Carbon::now()->startOfWeek();
                $dateTo = Carbon::now()->endOfWeek();
                break;
            case 'yearly':
                $dateFrom = Carbon::now()->startOfYear();
                $dateTo = Carbon::now()->endOfYear();
                break;
            case 'monthly':
            default:
                $dateFrom = Carbon::now()->startOfMonth();
                $dateTo = Carbon::now()->endOfMonth();
                break;
        }

        // Allow custom date range if provided
        if ($request->has('date_from') && $request->has('date_to')) {
            $dateFrom = Carbon::parse($request->date_from);
            $dateTo = Carbon::parse($request->date_to);
        }

        $query = Event::with(['dojo'])
            ->withCount('registrations')
            ->whereBetween('event_date', [$dateFrom, $dateTo]);

        if ($dojoId) {
            $query->where('dojo_id', $dojoId);
        }

        $events = $query->orderBy('event_date', 'desc')->get();

        // Calculate stats
        $totalEvents = $events->count();
        $totalParticipants = $events->sum('registrations_count');
        $totalRevenue = $events->sum(function($event) {
            return ($event->registration_fee ?? 0) * $event->registrations_count;
        });

        $dojos = Dojo::all();

        // Handle export
        if ($export === 'csv') {
            return $this->exportEventsCSV($events, $dateFrom, $dateTo);
        } elseif ($export === 'pdf') {
            return $this->exportEventsPDF($events, $dateFrom, $dateTo);
        }

        return view('admin.reports.events', compact(
            'events', 
            'totalEvents', 
            'totalParticipants', 
            'totalRevenue',
            'dateFrom', 
            'dateTo',
            'period',
            'dojoId',
            'dojos'
        ));
    }

    // Export Revenue to CSV
    private function exportRevenueCSV($invoices, $dateFrom, $dateTo, $totalRevenue)
    {
        $filename = 'revenue_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($invoices, $totalRevenue) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Invoice No', 'Date', 'Dojo', 'Member', 'Type', 'Amount']);
            
            // Data
            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->invoice_number,
                    $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : $invoice->created_at->format('Y-m-d'),
                    $invoice->member->dojo->name ?? 'N/A',
                    $invoice->member->fullname ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $invoice->invoice_type)),
                    'RM ' . number_format($invoice->total_amount, 0),
                ]);
            }
            
            // Total
            fputcsv($file, ['', '', '', '', 'TOTAL', 'RM ' . number_format($totalRevenue, 0)]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export Revenue to PDF (simple HTML to PDF)
    private function exportRevenuePDF($invoices, $dateFrom, $dateTo, $totalRevenue)
    {
        $html = view('admin.reports.revenue-pdf', compact('invoices', 'dateFrom', 'dateTo', 'totalRevenue'))->render();
        
        $filename = 'revenue_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.pdf';
        
        // For now, return HTML view (you can integrate DomPDF later)
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    // Export Events to CSV
    private function exportEventsCSV($events, $dateFrom, $dateTo)
    {
        $filename = 'events_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Event Name', 'Date', 'Dojo', 'Type', 'Participants', 'Fee', 'Total Revenue']);
            
            // Data
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->name,
                    $event->event_date->format('Y-m-d'),
                    $event->dojo->name ?? 'All Dojos',
                    ucfirst($event->type),
                    $event->registrations_count,
                    'RM ' . number_format($event->registration_fee ?? 0, 0),
                    'RM ' . number_format(($event->registration_fee ?? 0) * $event->registrations_count, 0),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export Events to PDF
    private function exportEventsPDF($events, $dateFrom, $dateTo)
    {
        $html = view('admin.reports.events-pdf', compact('events', 'dateFrom', 'dateTo'))->render();
        
        $filename = 'events_report_' . $dateFrom->format('Y-m-d') . '_to_' . $dateTo->format('Y-m-d') . '.pdf';
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
