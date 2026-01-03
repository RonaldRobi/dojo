<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\DojoClass;
use App\Models\Instructor;
use App\Models\Event;
use App\Models\Dojo;
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

    // Laporan Event
    public function events(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->subYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', Carbon::now()->format('Y-m-d'));

        $events = Event::with(['dojo'])
            ->withCount('registrations')
            ->whereBetween('event_date', [$dateFrom, $dateTo])
            ->orderBy('event_date', 'desc')
            ->get();

        return view('admin.reports.events', compact('events', 'dateFrom', 'dateTo'));
    }
}
