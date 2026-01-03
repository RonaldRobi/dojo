<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ClassEnrollment;
use App\Models\ClassSchedule;
use App\Models\Member;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = ClassEnrollment::whereHas('member', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with(['member', 'classSchedule.dojoClass']);

        if ($request->has('class_schedule_id')) {
            $query->where('class_schedule_id', $request->class_schedule_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->latest()->paginate(50);
        $schedules = ClassSchedule::whereHas('dojoClass', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with('dojoClass')->get();

        return view('owner.enrollments.index', compact('enrollments', 'schedules'));
    }

    public function enroll(Request $request)
    {
        $validated = $request->validate([
            'class_schedule_id' => 'required|exists:class_schedules,id',
            'member_id' => 'required|exists:members,id',
        ]);

        $schedule = ClassSchedule::findOrFail($validated['class_schedule_id']);

        try {
            $enrollment = $this->scheduleService->enrollMember($schedule, $validated['member_id']);
            return redirect()->route('owner.enrollments.index')
                ->with('success', 'Member enrolled successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function unenroll(ClassEnrollment $enrollment)
    {
        $enrollment->update(['status' => 'dropped']);
        return redirect()->back()
            ->with('success', 'Member unenrolled successfully.');
    }
}
