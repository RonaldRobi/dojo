<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementRecipient;
use App\Models\Instructor;
use App\Models\Member;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class BroadcastingController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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

        // Get all active students in the dojo
        $students = Member::where('dojo_id', $dojoId)
            ->where('status', 'active')
            ->with(['user', 'currentBelt'])
            ->orderBy('name')
            ->get();

        return view('coach.broadcasting.index', compact('students', 'instructor'));
    }

    public function store(Request $request)
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

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
            'priority' => 'nullable|in:low,normal,high',
        ]);

        // Verify all members are in the same dojo
        $students = Member::whereIn('id', $validated['member_ids'])
            ->where('dojo_id', $dojoId)
            ->where('status', 'active')
            ->get();

        if ($students->count() !== count($validated['member_ids'])) {
            return redirect()->route('coach.broadcasting.index')
                ->with('error', 'Some selected students are not valid.');
        }

        // Create announcement
        $announcement = Announcement::create([
            'dojo_id' => $dojoId,
            'title' => $validated['title'],
            'content' => $validated['message'],
            'target_audience' => 'students',
            'publish_at' => now(),
            'is_published' => true,
            'priority' => $validated['priority'] ?? 'normal',
        ]);

        // Send notifications to students
        $userIds = $students->pluck('user_id')->filter()->toArray();
        
        foreach ($userIds as $userId) {
            // Create announcement recipient
            AnnouncementRecipient::create([
                'announcement_id' => $announcement->id,
                'user_id' => $userId,
                'notification_sent_at' => now(),
            ]);

            // Create notification
            Notification::create([
                'user_id' => $userId,
                'type' => 'announcement',
                'title' => $validated['title'],
                'message' => substr($validated['message'], 0, 200),
                'link' => route('student.announcements.show', $announcement->id),
                'priority' => $validated['priority'] ?? 'normal',
            ]);
        }

        return redirect()->route('coach.broadcasting.index')
            ->with('success', "Broadcast sent to {$students->count()} student(s) successfully!");
    }
}

