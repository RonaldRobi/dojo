<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Announcement::where('dojo_id', $dojoId);

        if ($request->has('is_published')) {
            $query->where('is_published', $request->is_published);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $announcements = $query->latest()->paginate(20);

        return view('owner.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('owner.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_audience' => 'required|in:all,students,parents,instructors',
            'publish_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
            'priority' => 'nullable|in:low,normal,high',
        ]);

        $validated['dojo_id'] = currentDojo();
        $announcement = Announcement::create($validated);

        return redirect()->route('owner.announcements.show', $announcement)
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load(['recipients.user']);
        return view('owner.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('owner.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'target_audience' => 'sometimes|required|in:all,students,parents,instructors',
            'publish_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'is_published' => 'nullable|boolean',
            'priority' => 'nullable|in:low,normal,high',
        ]);

        $announcement->update($validated);

        return redirect()->route('owner.announcements.show', $announcement)
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('owner.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
