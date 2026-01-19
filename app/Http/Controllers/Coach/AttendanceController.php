<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\Instructor;
use App\Models\Member;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
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

        // Get class schedules for this instructor
        $classScheduleIds = ClassSchedule::where('instructor_id', $instructor->id)
            ->pluck('id');

        $query = Attendance::whereIn('class_schedule_id', $classScheduleIds)
            ->with(['member', 'classSchedule']);

        if ($request->has('class_schedule_id')) {
            $query->where('class_schedule_id', $request->class_schedule_id);
        }

        if ($request->has('date_from')) {
            $query->where('attendance_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('attendance_date', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $attendances = $query->latest('attendance_date')->paginate(50);
        $classSchedules = ClassSchedule::where('instructor_id', $instructor->id)
            ->get();

        return view('coach.attendance.index', compact('attendances', 'classSchedules', 'instructor'));
    }

    public function create(Request $request)
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

        // Get class schedules for this instructor
        $classSchedules = ClassSchedule::where('instructor_id', $instructor->id)
            ->where('is_active', true)
            ->get();

        $selectedScheduleId = $request->get('class_schedule_id');
        $selectedSchedule = null;
        
        // Get all active students in this dojo
        $students = Member::where('dojo_id', $dojoId)
            ->where('status', 'active')
            ->with('currentBelt')
            ->orderBy('name')
            ->get();

        if ($selectedScheduleId) {
            $selectedSchedule = ClassSchedule::where('id', $selectedScheduleId)
                ->where('instructor_id', $instructor->id)
                ->first();
        }

        return view('coach.attendance.create', compact('classSchedules', 'instructor', 'selectedScheduleId', 'selectedSchedule', 'students'));
    }

    public function store(Request $request)
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

        $validated = $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'attendance_date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.member_id' => 'required|exists:members,id',
            'attendances.*.status' => 'required|in:present,absent,excused',
            'attendances.*.notes' => 'nullable|string',
        ]);

        // Verify class schedule belongs to instructor
        $classSchedule = ClassSchedule::where('id', $validated['class_schedule_id'])
            ->where('instructor_id', $instructor->id)
            ->firstOrFail();

        $created = 0;
        foreach ($validated['attendances'] as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'member_id' => $attendanceData['member_id'],
                    'class_schedule_id' => $validated['class_schedule_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'status' => $attendanceData['status'],
                    'checked_in_at' => $attendanceData['status'] === 'present' ? now() : null,
                    'checked_in_method' => 'manual',
                    'notes' => $attendanceData['notes'] ?? null,
                ]
            );
            $created++;
        }

        return redirect()->route('coach.attendance.index')
            ->with('success', "Attendance recorded for {$created} student(s).");
    }

    public function bulkStore(Request $request)
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

        $validated = $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'attendance_date' => 'required|date',
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
            'status' => 'required|in:present,absent,excused',
        ]);

        // Verify class schedule belongs to instructor
        $classSchedule = ClassSchedule::where('id', $validated['class_schedule_id'])
            ->where('instructor_id', $instructor->id)
            ->firstOrFail();

        $created = 0;
        foreach ($validated['member_ids'] as $memberId) {
            Attendance::updateOrCreate(
                [
                    'member_id' => $memberId,
                    'class_schedule_id' => $validated['class_schedule_id'],
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'status' => $validated['status'],
                    'checked_in_at' => $validated['status'] === 'present' ? now() : null,
                    'checked_in_method' => 'manual',
                ]
            );
            $created++;
        }

        return redirect()->route('coach.attendance.index')
            ->with('success', "Attendance recorded for {$created} student(s).");
    }
}

