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

        // Get announcements for students (target_audience: 'all' or 'students')
        $announcements = \App\Models\Announcement::where('dojo_id', $dojoId)
            ->where('is_published', true)
            ->whereIn('target_audience', ['all', 'students'])
            ->with('dojo')
            ->latest()
            ->paginate(20);

        return view('student.announcements.index', compact('announcements', 'member'));
    }

    public function show($id)
    {
        $user = auth()->user();
        $member = Member::where('user_id', $user->id)->first();

        $announcement = \App\Models\Announcement::with('dojo')->findOrFail($id);

        // Check if member has access
        if ($announcement->dojo_id !== $member->dojo_id) {
            abort(403, 'You do not have access to this announcement.');
        }

        return view('student.announcements.show', compact('announcement', 'member'));
    }
}

