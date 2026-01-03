<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;
use App\Models\Member;
use App\Services\ProgressService;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    protected $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = ProgressLog::whereHas('member', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with(['member', 'instructor']);

        if ($request->has('member_id')) {
            $query->where('member_id', $request->member_id);
        }

        $logs = $query->paginate(50);

        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'instructor_id' => 'required|exists:instructors,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
            'skills_improved' => 'nullable|string',
            'areas_to_improve' => 'nullable|string',
            'curriculum_items_completed' => 'nullable|array',
        ]);

        $log = ProgressLog::create($validated);

        return response()->json($log, 201);
    }

    public function checkEligibility(Member $member, $rankId)
    {
        $rank = \App\Models\Rank::findOrFail($rankId);
        $eligibility = $this->progressService->checkEligibilityForRank($member, $rank);
        return response()->json($eligibility);
    }

    public function promote(Member $member, Request $request)
    {
        $validated = $request->validate([
            'rank_id' => 'required|exists:ranks,id',
            'instructor_id' => 'nullable|exists:instructors,id',
        ]);

        $rank = \App\Models\Rank::findOrFail($validated['rank_id']);
        $memberRank = $this->progressService->promoteMember(
            $member,
            $rank,
            $validated['instructor_id'] ?? null
        );

        return response()->json($memberRank, 201);
    }
}
