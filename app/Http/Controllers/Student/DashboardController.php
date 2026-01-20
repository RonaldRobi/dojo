<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Invoice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $member = Member::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->with(['currentBelt', 'enrollments.classSchedule.dojoClass', 'attendances', 'dojo'])
            ->first();

        if (!$member) {
            abort(404, 'Member not found');
        }

        // Check for unpaid registration invoice
        $unpaidRegistration = Invoice::where('member_id', $member->id)
            ->where('type', 'registration')
            ->whereIn('status', ['pending', 'overdue'])
            ->first();

        $stats = [
            'member' => $member,
            'current_belt' => $member->currentBelt,
            'enrolled_classes' => $member->enrollments()->where('status', 'active')->with('classSchedule.dojoClass')->get(),
            'monthly_attendance' => $member->attendances()
                ->whereMonth('attendance_date', now()->month)
                ->where('status', 'present')
                ->count(),
            'total_classes' => $member->enrollments()->where('status', 'active')->count(),
            'upcoming_events' => \App\Models\EventRegistration::where('member_id', $member->id)
                ->whereHas('event', function($q) {
                    $q->where('event_date', '>=', now());
                })
                ->with('event')
                ->orderBy('created_at')
                ->limit(5)
                ->get(),
            'recent_attendance' => $member->attendances()
                ->latest('attendance_date')
                ->limit(10)
                ->get(),
        ];

        return view('dashboard.student', compact('stats', 'unpaidRegistration'));
    }
}
