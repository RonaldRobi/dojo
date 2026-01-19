<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Instructor;
use Illuminate\Http\Request;

class StudentController extends Controller
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

        // Get all students in the dojo
        $query = Member::where('dojo_id', $dojoId)
            ->where('status', 'active')
            ->with(['currentBelt']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(20);

        // Get class schedules for filter (optional, jika masih dibutuhkan)
        $classSchedules = \App\Models\ClassSchedule::where('instructor_id', $instructor->id)
            ->where('is_active', true)
            ->get();

        return view('coach.students.index', compact('students', 'classSchedules', 'instructor'));
    }

    public function show(Member $member)
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

        // Verify member is in same dojo
        if ($member->dojo_id !== $dojoId) {
            abort(403, 'You do not have access to this student.');
        }

        $member->load([
            'currentBelt',
            'attendances' => function($q) use ($instructor) {
                $q->whereHas('classSchedule', function($query) use ($instructor) {
                    $query->where('instructor_id', $instructor->id);
                })->latest('attendance_date')->limit(20);
            },
            'progressLogs' => function($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id)->latest()->limit(10);
            }
        ]);

        return view('coach.students.show', compact('member', 'instructor'));
    }
}

