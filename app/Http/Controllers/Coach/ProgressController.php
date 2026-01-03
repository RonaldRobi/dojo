<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\ProgressLog;
use App\Models\Instructor;
use Illuminate\Http\Request;

class ProgressController extends Controller
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

        $query = Member::whereHas('enrollments.classSchedule', function($q) use ($instructor) {
            $q->where('instructor_id', $instructor->id);
        })->with(['currentBelt', 'enrollments.classSchedule']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $members = $query->paginate(20);
        $progressLogs = ProgressLog::where('instructor_id', $instructor->id)
            ->with('member')
            ->latest()
            ->limit(10)
            ->get();

        return view('coach.progress.index', compact('members', 'progressLogs', 'instructor'));
    }

    public function show(Member $member)
    {
        $user = auth()->user();
        $instructor = Instructor::where('user_id', $user->id)->first();

        // Verify member is in instructor's class
        $hasAccess = $member->enrollments()
            ->whereHas('classSchedule', function($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this member.');
        }

        $member->load(['currentBelt', 'enrollments.classSchedule.dojoClass', 'attendances']);
        $progressLogs = ProgressLog::where('member_id', $member->id)
            ->where('instructor_id', $instructor->id)
            ->latest()
            ->get();
        $ranks = \App\Models\Rank::where('dojo_id', $member->dojo_id)->orderBy('level')->get();

        return view('coach.progress.show', compact('member', 'progressLogs', 'ranks', 'instructor'));
    }

    public function store(Request $request, Member $member)
    {
        $user = auth()->user();
        $instructor = Instructor::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'skills_improved' => 'nullable|string',
            'areas_to_improve' => 'nullable|string',
        ]);

        $validated['member_id'] = $member->id;
        $validated['instructor_id'] = $instructor->id;

        ProgressLog::create($validated);

        return redirect()->route('coach.progress.show', $member)
            ->with('success', 'Progress log added successfully.');
    }
}

