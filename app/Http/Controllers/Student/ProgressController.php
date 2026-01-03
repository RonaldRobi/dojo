<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $member = Member::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->with(['currentBelt', 'memberRanks.rank'])
            ->first();

        if (!$member) {
            abort(404, 'Member profile not found');
        }

        $rankHistory = \App\Models\MemberRank::where('member_id', $member->id)
            ->with('rank')
            ->orderBy('promoted_at', 'desc')
            ->get();

        $progressLogs = \App\Models\ProgressLog::where('member_id', $member->id)
            ->with('instructor')
            ->latest()
            ->limit(20)
            ->get();

        $nextRank = \App\Models\Rank::where('dojo_id', $dojoId)
            ->where('level', '>', $member->currentBelt->level ?? 0)
            ->orderBy('level')
            ->first();

        return view('student.progress.index', compact('member', 'rankHistory', 'progressLogs', 'nextRank'));
    }
}

