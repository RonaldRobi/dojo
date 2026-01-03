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
        $dojoId = currentDojo();

        $children = Member::whereHas('parents', function($q) use ($user, $dojoId) {
            $q->where('parent_user_id', $user->id)
              ->where('dojo_id', $dojoId);
        })->with(['currentBelt', 'enrollments.classSchedule.dojoClass'])->get();

        return view('parent.children.index', compact('children'));
    }

    public function show(Member $member)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        // Verify this is the user's child
        $isChild = $member->parents()
            ->where('parent_user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->exists();

        if (!$isChild) {
            abort(403, 'You do not have access to this member.');
        }

        $member->load(['currentBelt', 'enrollments.classSchedule.dojoClass', 'attendances', 'memberRanks.rank']);
        
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
}

