<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $childId = $request->input('child_id');
        
        $children = Member::whereHas('parents', function($q) use ($user, $dojoId) {
            $q->where('parent_user_id', $user->id)
              ->where('dojo_id', $dojoId);
        })->get();

        $selectedChild = $childId 
            ? $children->firstWhere('id', $childId)
            : $children->first();

        if (!$selectedChild) {
            return view('parent.schedules.index', ['children' => $children]);
        }

        $enrollments = $selectedChild->enrollments()
            ->where('status', 'active')
            ->with(['classSchedule.dojoClass', 'classSchedule.instructor'])
            ->get();

        // Group by day of week
        $schedules = [];
        foreach ($enrollments as $enrollment) {
            $day = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$enrollment->classSchedule->day_of_week];
            if (!isset($schedules[$day])) {
                $schedules[$day] = [];
            }
            $schedules[$day][] = $enrollment->classSchedule;
        }

        return view('parent.schedules.index', compact('children', 'selectedChild', 'schedules'));
    }
}

