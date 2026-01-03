<?php

namespace App\Services;

use App\Models\MemberMembership;
use App\Models\Membership;
use App\Models\Member;
use Carbon\Carbon;

class MembershipService
{
    public function activateMembership(Member $member, Membership $membership): MemberMembership
    {
        $startDate = now();
        $endDate = $startDate->copy()->addDays($membership->duration_days);

        return MemberMembership::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
        ]);
    }

    public function checkExpiredMemberships(): void
    {
        MemberMembership::where('status', 'active')
            ->where('end_date', '<', now())
            ->update(['status' => 'expired']);
    }

    public function renewMembership(MemberMembership $memberMembership): MemberMembership
    {
        $membership = $memberMembership->membership;
        $newEndDate = $memberMembership->end_date->copy()->addDays($membership->duration_days);

        $memberMembership->update([
            'end_date' => $newEndDate,
            'status' => 'active',
        ]);

        return $memberMembership->fresh();
    }
}

