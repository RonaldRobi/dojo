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

        $classes = ClassSchedule::where('instructor_id', $instructor->id)
            ->with(['dojoClass', 'enrollments.member'])
            ->where('is_active', true)
            ->get()
            ->groupBy(function($schedule) {
                return $schedule->dojoClass->name ?? 'Unnamed Class';
            });

        return view('coach.classes.index', compact('classes', 'instructor'));
    }

    public function show(ClassSchedule $classSchedule)
    {
        $user = auth()->user();
        $instructor = Instructor::where('user_id', $user->id)->first();

        if ($classSchedule->instructor_id !== $instructor->id) {
            abort(403, 'You do not have access to this class.');
        }

        $classSchedule->load(['dojoClass', 'enrollments.member', 'attendances.member']);
        $students = $classSchedule->enrollments()->with('member')->where('status', 'active')->get();

        return view('coach.classes.show', compact('classSchedule', 'students'));
    }
}

