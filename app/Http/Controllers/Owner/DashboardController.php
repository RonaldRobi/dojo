<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\DojoClass;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Event;
use App\Models\ClassSchedule;
use App\Models\Attendance;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService = null)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $dojoId = currentDojo();
        $dojo = \App\Models\Dojo::find($dojoId);

        if (!$dojo) {
            abort(404, 'Dojo not found');
        }

        // Basic Stats
        $stats = [
            'dojo_name' => $dojo->name,
            'dojo_address' => $dojo->address,
            
            // Member Stats
            'total_members' => Member::where('dojo_id', $dojoId)->count(),
            'active_members' => Member::where('dojo_id', $dojoId)->where('status', 'active')->count(),
            'new_members_this_month' => Member::where('dojo_id', $dojoId)
                ->whereMonth('join_date', Carbon::now()->month)
                ->count(),
            
            // User Stats
            'total_users' => User::where('dojo_id', $dojoId)->count(),
            'active_users' => User::where('dojo_id', $dojoId)->where('status', 'active')->count(),
            
            // Instructor Stats
            'total_instructors' => Instructor::where('dojo_id', $dojoId)->count(),
            
            // Class & Schedule Stats
            'total_schedules' => ClassSchedule::where('dojo_id', $dojoId)->count(),
            'active_schedules' => ClassSchedule::where('dojo_id', $dojoId)->where('is_active', true)->count(),
            
            // Event Stats
            'upcoming_events' => Event::where('dojo_id', $dojoId)
                ->where('event_date', '>=', Carbon::now())
                ->count(),
            
            // Financial Stats
            'total_revenue' => Invoice::where('dojo_id', $dojoId)
                ->where('status', 'paid')
                ->sum('total_amount'),
            'pending_invoices' => Invoice::where('dojo_id', $dojoId)
                ->where('status', 'pending')
                ->count(),
            'pending_amount' => Invoice::where('dojo_id', $dojoId)
                ->where('status', 'pending')
                ->sum('total_amount'),
            
            // Attendance (Today)
            'today_attendance' => Attendance::whereHas('member', function($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId);
            })->whereDate('attendance_date', Carbon::today())->count(),
        ];

        // Recent activities
        $recentMembers = Member::where('dojo_id', $dojoId)
            ->latest()
            ->take(5)
            ->get();
            
        $upcomingEvents = Event::where('dojo_id', $dojoId)
            ->where('event_date', '>=', Carbon::now())
            ->orderBy('event_date')
            ->take(5)
            ->get();

        return view('dashboard.owner', compact('stats', 'dojo', 'recentMembers', 'upcomingEvents'));
    }
}
