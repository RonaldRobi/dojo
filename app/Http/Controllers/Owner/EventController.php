<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = Event::where(function($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId)
                  ->orWhereNull('dojo_id'); // Include events for all dojos
            })
            ->with(['registrations.member', 'dojo']);

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $events = $query->latest('event_date')->paginate(20);

        return view('owner.events.index', compact('events'));
    }

    public function create()
    {
        return view('owner.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:grading,sparring,tournament,seminar,workshop',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'registration_deadline' => 'nullable|date',
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
        ]);

        $validated['dojo_id'] = currentDojo();
        $event = Event::create($validated);

        return redirect()->route('owner.events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['registrations.member', 'dojo']);
        return view('owner.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        // Check if event is for all dojos
        if (!$event->dojo_id) {
            abort(403, 'You cannot edit events for all dojos.');
        }
        
        return view('owner.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        // Check if event is for all dojos
        if (!$event->dojo_id) {
            abort(403, 'You cannot update events for all dojos.');
        }
        
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:grading,sparring,tournament,seminar,workshop',
            'description' => 'nullable|string',
            'event_date' => 'sometimes|required|date',
            'registration_deadline' => 'nullable|date',
            'location' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'is_public' => 'nullable|boolean',
        ]);

        $event->update($validated);

        return redirect()->route('owner.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Check if event is for all dojos
        if (!$event->dojo_id) {
            abort(403, 'You cannot delete events for all dojos.');
        }
        
        $event->delete();
        return redirect()->route('owner.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
