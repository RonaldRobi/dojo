<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Rank;
use App\Models\MemberRank;
use App\Models\Attendance;
use Carbon\Carbon;

class ProgressService
{
    public function checkEligibilityForRank(Member $member, Rank $rank): array
    {
        $eligibility = [
            'eligible' => true,
            'requirements' => [],
            'missing' => [],
        ];

        // Check attendance requirement
        $attendanceRequirement = $rank->rankRequirements()
            ->where('requirement_type', 'attendance_min')
            ->first();

        if ($attendanceRequirement) {
            $minAttendance = (int) $attendanceRequirement->requirement_value;
            $actualAttendance = Attendance::where('member_id', $member->id)
                ->where('status', 'present')
                ->count();

            $eligibility['requirements']['attendance'] = [
                'required' => $minAttendance,
                'actual' => $actualAttendance,
                'met' => $actualAttendance >= $minAttendance,
            ];

            if ($actualAttendance < $minAttendance) {
                $eligibility['eligible'] = false;
                $eligibility['missing'][] = "Minimum attendance: {$minAttendance} (current: {$actualAttendance})";
            }
        }

        return $eligibility;
    }

    public function promoteMember(Member $member, Rank $rank, $instructorId = null): MemberRank
    {
        $memberRank = MemberRank::create([
            'member_id' => $member->id,
            'rank_id' => $rank->id,
            'achieved_at' => now(),
            'awarded_by_instructor_id' => $instructorId,
        ]);

        // Update member's current belt
        $member->update([
            'current_belt_id' => $rank->id,
            'current_level' => $rank->level,
        ]);

        return $memberRank;
    }
}

