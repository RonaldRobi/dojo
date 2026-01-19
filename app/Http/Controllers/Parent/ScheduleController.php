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
        
        // Get ALL children from ALL dojos
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');
        
        $children = Member::whereIn('id', $memberIds)
            ->with(['dojo'])
            ->get();

        if ($children->isEmpty()) {
            return view('parent.schedules.index', ['children' => $children, 'weeklySchedules' => []]);
        }

        // Get all dojos where children are enrolled
        $dojoIds = $children->pluck('dojo_id')->unique();

        // Get all class schedules from all dojos
        $allSchedules = \App\Models\ClassSchedule::whereIn('dojo_id', $dojoIds)
            ->where('is_active', true)
            ->with(['instructor', 'dojo'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Build weekly schedule structure with children info
        $weeklySchedules = [];
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        foreach ($days as $index => $day) {
            $weeklySchedules[$index] = [
                'day' => $day,
                'schedules' => []
            ];
        }

        // Group schedules by day and attach child info
        foreach ($allSchedules as $schedule) {
            // Find which children belong to this dojo
            $childrenInDojo = $children->where('dojo_id', $schedule->dojo_id);
            
            foreach ($childrenInDojo as $child) {
                $weeklySchedules[$schedule->day_of_week]['schedules'][] = [
                    'schedule' => $schedule,
                    'child' => $child,
                    'time' => $schedule->start_time . ' - ' . $schedule->end_time,
                ];
            }
        }

        // Sort schedules within each day by time
        foreach ($weeklySchedules as &$daySchedule) {
            usort($daySchedule['schedules'], function($a, $b) {
                return strcmp($a['schedule']->start_time, $b['schedule']->start_time);
            });
        }

        return view('parent.schedules.index', compact('children', 'weeklySchedules'));
    }
}

