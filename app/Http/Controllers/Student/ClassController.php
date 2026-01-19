<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $member = Member::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->first();

        if (!$member) {
            abort(404, 'Member profile not found');
        }

        // Get all class schedules for this dojo, grouped by day
        $schedules = ClassSchedule::where('dojo_id', $dojoId)
            ->where('is_active', true)
            ->with(['instructor'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Group schedules by day of week
        $schedulesByDay = [];
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        foreach ($days as $index => $day) {
            $schedulesByDay[$day] = $schedules->filter(function ($schedule) use ($index) {
                return $schedule->day_of_week == $index;
            });
        }

        return view('student.classes.index', compact('schedulesByDay', 'member', 'schedules'));
    }

    public function show($scheduleId)
    {
        $user = auth()->user();
        $dojoId = currentDojo();
        
        $member = Member::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->first();

        if (!$member) {
            abort(404, 'Member profile not found');
        }

        $schedule = ClassSchedule::where('id', $scheduleId)
            ->where('dojo_id', $dojoId)
            ->with(['instructor'])
            ->firstOrFail();

        // Get student's attendance for this schedule
        $attendances = \App\Models\Attendance::where('member_id', $member->id)
            ->where('class_schedule_id', $scheduleId)
            ->latest('attendance_date')
            ->paginate(10);

        return view('student.classes.show', compact('schedule', 'attendances', 'member'));
    }
}

