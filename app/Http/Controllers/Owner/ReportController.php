<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Invoice;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $dojoId = currentDojo();
        $dojo = \App\Models\Dojo::find($dojoId);
        
        if (!$dojo) {
            abort(403, 'You do not have access to any dojo.');
        }

        $period = $request->get('period', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Determine date range based on period
        if (!$dateFrom || !$dateTo) {
            switch ($period) {
                case 'weekly':
                    $dateFrom = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $dateTo = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'yearly':
                    $dateFrom = Carbon::now()->startOfYear()->format('Y-m-d');
                    $dateTo = Carbon::now()->endOfYear()->format('Y-m-d');
                    break;
                case 'monthly':
                default:
                    $dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
            }
        }

        // Filter invoices by owner's dojo
        $query = Invoice::with(['member.user'])
            ->whereHas('member', function ($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            })
            ->where('status', 'paid')
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);

        $invoices = $query->orderBy('created_at', 'desc')->get();

        $totalRevenue = $invoices->sum('amount');
        $totalInvoices = $invoices->count();
        $averagePerInvoice = $totalInvoices > 0 ? $totalRevenue / $totalInvoices : 0;

        // Group by invoice type for summary
        $revenueByType = $invoices->groupBy('type')->map(function ($typeInvoices) {
            return $typeInvoices->sum('amount');
        });

        // Export logic
        if ($request->get('export') === 'csv') {
            $filename = 'revenue_report_' . $dateFrom . '_to_' . $dateTo . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($invoices, $totalRevenue, $totalInvoices, $averagePerInvoice, $revenueByType, $dateFrom, $dateTo, $dojo) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Revenue Report - ' . $dojo->name]);
                fputcsv($file, ['Period: ' . $dateFrom . ' - ' . $dateTo]);
                fputcsv($file, ['Generated: ' . now()->format('d M Y H:i:s')]);
                fputcsv($file, []);

                fputcsv($file, ['Summary']);
                fputcsv($file, ['Total Revenue', 'Total Invoices', 'Average per Invoice']);
                fputcsv($file, [number_format($totalRevenue, 0), number_format($totalInvoices), number_format($averagePerInvoice, 0)]);
                fputcsv($file, []);

                fputcsv($file, ['Revenue by Type']);
                foreach ($revenueByType as $type => $amount) {
                    fputcsv($file, [$type, number_format($amount, 0)]);
                }
                fputcsv($file, []);

                fputcsv($file, ['Invoice Details']);
                fputcsv($file, ['Invoice No', 'Date', 'Member Name', 'Invoice Type', 'Amount']);
                foreach ($invoices as $invoice) {
                    fputcsv($file, [
                        $invoice->invoice_number,
                        Carbon::parse($invoice->created_at)->format('d M Y'),
                        $invoice->member->name ?? 'N/A',
                        ucfirst(str_replace('_', ' ', $invoice->type)),
                        number_format($invoice->amount, 0),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } elseif ($request->get('export') === 'pdf') {
            $pdf = \PDF::loadView('owner.reports.revenue-pdf', compact('invoices', 'totalRevenue', 'totalInvoices', 'averagePerInvoice', 'revenueByType', 'dateFrom', 'dateTo', 'dojo'));
            return $pdf->stream('revenue_report_' . $dateFrom . '_to_' . $dateTo . '.pdf');
        }

        return view('owner.reports.revenue', compact('invoices', 'dojo', 'period', 'dateFrom', 'dateTo', 'totalRevenue', 'totalInvoices', 'averagePerInvoice', 'revenueByType'));
    }

    public function events(Request $request)
    {
        $dojoId = currentDojo();
        $dojo = \App\Models\Dojo::find($dojoId);
        
        if (!$dojo) {
            abort(403, 'You do not have access to any dojo.');
        }

        $period = $request->get('period', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Determine date range based on period
        if (!$dateFrom || !$dateTo) {
            switch ($period) {
                case 'weekly':
                    $dateFrom = Carbon::now()->startOfWeek()->format('Y-m-d');
                    $dateTo = Carbon::now()->endOfWeek()->format('Y-m-d');
                    break;
                case 'yearly':
                    $dateFrom = Carbon::now()->startOfYear()->format('Y-m-d');
                    $dateTo = Carbon::now()->endOfYear()->format('Y-m-d');
                    break;
                case 'monthly':
                default:
                    $dateFrom = Carbon::now()->startOfMonth()->format('Y-m-d');
                    $dateTo = Carbon::now()->endOfMonth()->format('Y-m-d');
                    break;
            }
        }

        $query = Event::withCount('registrations')
            ->where('dojo_id', $dojoId)
            ->whereBetween(DB::raw('DATE(event_date)'), [$dateFrom, $dateTo]);

        $events = $query->orderBy('event_date', 'desc')->get();

        $totalEvents = $events->count();
        $totalParticipants = $events->sum('registrations_count');
        $eventRevenue = $events->sum(function($e) {
            return ($e->registration_fee ?? 0) * $e->registrations_count;
        });

        // Export logic
        if ($request->get('export') === 'csv') {
            $filename = 'event_report_' . $dateFrom . '_to_' . $dateTo . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function () use ($events, $totalEvents, $totalParticipants, $eventRevenue, $dateFrom, $dateTo, $dojo) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Event Reports - ' . $dojo->name]);
                fputcsv($file, ['Period: ' . $dateFrom . ' - ' . $dateTo]);
                fputcsv($file, ['Generated: ' . now()->format('d M Y H:i:s')]);
                fputcsv($file, []);

                fputcsv($file, ['Summary']);
                fputcsv($file, ['Total Events', 'Total Participants', 'Event Revenue']);
                fputcsv($file, [number_format($totalEvents), number_format($totalParticipants), number_format($eventRevenue, 0)]);
                fputcsv($file, []);

                fputcsv($file, ['Event Details']);
                fputcsv($file, ['Event Name', 'Date', 'Type', 'Participants', 'Fee', 'Revenue']);
                foreach ($events as $event) {
                    fputcsv($file, [
                        $event->name,
                        Carbon::parse($event->event_date)->format('d M Y'),
                        ucfirst($event->type),
                        $event->registrations_count,
                        number_format($event->registration_fee ?? 0, 0),
                        number_format(($event->registration_fee ?? 0) * $event->registrations_count, 0),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } elseif ($request->get('export') === 'pdf') {
            $pdf = \PDF::loadView('owner.reports.events-pdf', compact('events', 'totalEvents', 'totalParticipants', 'eventRevenue', 'dateFrom', 'dateTo', 'dojo'));
            return $pdf->stream('event_report_' . $dateFrom . '_to_' . $dateTo . '.pdf');
        }

        return view('owner.reports.events', compact('events', 'dojo', 'period', 'dateFrom', 'dateTo', 'totalEvents', 'totalParticipants', 'eventRevenue'));
    }
}
