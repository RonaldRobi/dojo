<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get ALL children across ALL dojos (parent can access all dojos)
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');

        $children = Member::whereIn('id', $memberIds)
            ->with(['currentBelt', 'dojo'])
            ->get();

        return view('parent.children.index', compact('children'));
    }

    public function show(Member $member)
    {
        $user = auth()->user();

        // Verify this is the user's child (from any dojo)
        $isChild = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->where('member_id', $member->id)
            ->exists();

        if (!$isChild) {
            abort(403, 'You do not have access to this member.');
        }

        $member->load(['currentBelt', 'attendances', 'ranks.rank', 'dojo']);
        
        $attendances = $member->attendances()
            ->latest('attendance_date')
            ->limit(30)
            ->get();

        $invoices = \App\Models\Invoice::where('member_id', $member->id)
            ->with(['items', 'payments'])
            ->latest()
            ->limit(10)
            ->get();

        $totalDue = \App\Models\Invoice::where('member_id', $member->id)
            ->where('status', '!=', 'paid')
            ->sum('total_amount');

        return view('parent.children.show', compact('member', 'attendances', 'invoices', 'totalDue'));
    }

    public function progress(Member $member)
    {
        $user = auth()->user();

        // Verify this is the user's child (from any dojo)
        $isChild = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->where('member_id', $member->id)
            ->exists();

        if (!$isChild) {
            abort(403, 'You do not have access to this member.');
        }

        // Load all progress data
        $member->load([
            'currentBelt',
            'ranks.rank',
            'gradingResults.rank',
            'gradingResults.instructor',
            'progressLogs.instructor',
            'dojo',
            'attendances' => function($q) {
                $q->latest('attendance_date')->limit(100);
            }
        ]);

        // Get statistics
        $totalAttendances = $member->attendances()->where('status', 'present')->count();
        $totalClasses = $member->attendances()->count(); // Total classes attended (not enrollments)
        $currentRank = $member->currentBelt;
        $allRanks = $member->ranks()->with('rank')->orderBy('achieved_at', 'desc')->get();
        $gradingResults = $member->gradingResults()->with(['rank', 'instructor'])->latest('grading_date')->get();
        $progressLogs = $member->progressLogs()->with('instructor')->latest('date')->get();

        // Calculate attendance rate (last 30 days)
        $recentAttendances = $member->attendances()
            ->where('attendance_date', '>=', now()->subDays(30))
            ->where('status', 'present')
            ->count();
        $recentTotal = $member->attendances()
            ->where('attendance_date', '>=', now()->subDays(30))
            ->count();
        $attendanceRate = $recentTotal > 0 ? round(($recentAttendances / $recentTotal) * 100, 2) : 0;

        return view('parent.children.progress', compact(
            'member',
            'totalAttendances',
            'totalClasses',
            'currentRank',
            'allRanks',
            'gradingResults',
            'progressLogs',
            'attendanceRate'
        ));
    }
}

