<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Instructor;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $instructor = Instructor::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->first();

        if (!$instructor) {
            return redirect()->route('coach.dashboard')
                ->with('error', 'Instructor profile not found.');
        }

        $schedules = ClassSchedule::where('instructor_id', $instructor->id)
            ->where('is_active', true)
            ->orderBy('class_name')
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('coach.classes.index', compact('schedules', 'instructor'));
    }

    public function show(ClassSchedule $classSchedule)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $instructor = Instructor::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->first();

        if (!$instructor || $classSchedule->instructor_id !== $instructor->id) {
            abort(403, 'You do not have access to this class.');
        }

        // Get all students in dojo
        $students = \App\Models\Member::where('dojo_id', $dojoId)
            ->where('status', 'active')
            ->with('currentBelt')
            ->orderBy('name')
            ->get();

        // Get attendance records for this schedule
        $classSchedule->load(['attendances' => function($q) {
            $q->with('member')->latest('attendance_date')->limit(50);
        }]);

        return view('coach.classes.show', compact('classSchedule', 'students'));
    }
}

