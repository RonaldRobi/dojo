<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        // Get children (students linked to this parent)
        $children = Member::whereHas('parents', function($q) use ($user, $dojoId) {
            $q->where('parent_user_id', $user->id)
              ->where('dojo_id', $dojoId);
        })->with(['currentBelt', 'enrollments.classSchedule.dojoClass', 'dojo'])->get();

        $selectedChildId = $request->input('child_id');
        $selectedChild = $selectedChildId 
            ? $children->firstWhere('id', $selectedChildId)
            : $children->first();

        $stats = [
            'children' => $children,
            'selected_child' => $selectedChild,
            'selected_child_attendance' => $selectedChild ? $selectedChild->attendances()
                ->whereMonth('attendance_date', now()->month)
                ->where('status', 'present')
                ->count() : 0,
            'selected_child_total_classes' => $selectedChild ? $selectedChild->enrollments()->where('status', 'active')->count() : 0,
            'selected_child_progress' => $selectedChild ? $selectedChild->currentBelt : null,
            'pending_payments' => $selectedChild ? \App\Models\Invoice::where('member_id', $selectedChild->id)
                ->where('status', 'pending')
                ->with('items')
                ->get() : collect(),
            'upcoming_events' => $selectedChild ? \App\Models\EventRegistration::where('member_id', $selectedChild->id)
                ->whereHas('event', function($q) {
                    $q->where('event_date', '>=', now());
                })
                ->with('event')
                ->limit(5)
                ->get() : collect(),
        ];

        return view('dashboard.parent', compact('stats'));
    }
}
