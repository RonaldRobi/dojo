<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Member;
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

        $enrollments = $member->enrollments()
            ->where('status', 'active')
            ->with(['classSchedule.dojoClass', 'classSchedule.instructor'])
            ->get();

        return view('student.classes.index', compact('enrollments', 'member'));
    }

    public function show($enrollmentId)
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->first();

        $enrollment = \App\Models\ClassEnrollment::where('id', $enrollmentId)
            ->where('member_id', $member->id)
            ->with(['classSchedule.dojoClass', 'classSchedule.instructor'])
            ->firstOrFail();

        $attendances = \App\Models\Attendance::where('member_id', $member->id)
            ->where('class_schedule_id', $enrollment->class_schedule_id)
            ->latest('attendance_date')
            ->get();

        return view('student.classes.show', compact('enrollment', 'attendances', 'member'));
    }
}

