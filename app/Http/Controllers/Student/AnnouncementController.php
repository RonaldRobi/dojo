<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $member = Member::where('user_id', $user->id)
            ->where('dojo_id', $dojoId)
            ->first();

        if (!$member) {
            abort(404, 'Member profile not found');
        }

        $announcements = \App\Models\Announcement::where('dojo_id', $dojoId)
            ->where(function($q) use ($member) {
                $q->where('target_type', 'all')
                  ->orWhere(function($query) use ($member) {
                      $query->where('target_type', 'member')
                            ->whereJsonContains('target_ids', $member->id);
                  })
                  ->orWhere(function($query) use ($member) {
                      $query->where('target_type', 'class')
                            ->whereIn('target_ids', $member->enrollments()->pluck('class_schedule_id')->toArray());
                  });
            })
            ->latest()
            ->paginate(20);

        return view('student.announcements.index', compact('announcements', 'member'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->first();

        $announcement = \App\Models\Announcement::findOrFail($id);

        // Check if member has access
        if ($announcement->dojo_id !== $member->dojo_id) {
            abort(403, 'You do not have access to this announcement.');
        }

        return view('student.announcements.show', compact('announcement', 'member'));
    }
}

