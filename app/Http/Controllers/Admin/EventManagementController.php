<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventCertificate;
use App\Models\Dojo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventManagementController extends Controller
{
    /**
     * Display a listing of all events (all dojos)
     */
    public function index(Request $request)
    {
        $query = Event::with(['dojo']);

        // Search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Dojo filter
        if ($request->has('dojo_id') && $request->dojo_id != '') {
            $query->where('dojo_id', $request->dojo_id);
        }

        // Type filter
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'upcoming') {
                $query->where('event_date', '>=', now());
            } elseif ($request->status == 'past') {
                $query->where('event_date', '<', now());
            }
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.index', compact('events', 'dojos'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        $dojos = Dojo::all();
        return view('admin.events.create', compact('dojos'));
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dojo_id' => 'nullable|exists:dojos,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:workshop,seminar,tournament,grading,sparring',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'registration_deadline' => 'nullable|date|before:event_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Set boolean defaults
        $validated['is_public'] = $request->has('is_public');
        $validated['is_active'] = $request->has('is_active') ? true : true; // Default active

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load(['dojo', 'registrations.member.user']);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit(Event $event)
    {
        $dojos = Dojo::all();
        return view('admin.events.edit', compact('event', 'dojos'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'dojo_id' => 'nullable|exists:dojos,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:workshop,seminar,tournament,grading,sparring',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'registration_deadline' => 'nullable|date|before:event_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Set boolean values
        $validated['is_public'] = $request->has('is_public');
        $validated['is_active'] = $request->has('is_active') ? true : false;

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    // Event Nasional
    public function national(Request $request)
    {
        $query = Event::with(['dojo'])
            ->where('is_public', true)
            ->orWhereNull('dojo_id');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.national', compact('events', 'dojos'));
    }

    // Turnamen Nasional
    public function tournaments(Request $request)
    {
        $query = Event::with(['dojo'])
            ->where('type', 'tournament')
            ->where(function($q) {
                $q->where('is_public', true)->orWhereNull('dojo_id');
            });

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.tournaments', compact('events', 'dojos'));
    }

    // Grading Nasional
    public function grading(Request $request)
    {
        $query = Event::with(['dojo'])
            ->where('type', 'grading')
            ->where(function($q) {
                $q->where('is_public', true)->orWhereNull('dojo_id');
            });

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.grading', compact('events', 'dojos'));
    }

    // Sertifikat Digital Global
    public function certificates(Request $request)
    {
        $query = EventCertificate::with(['eventRegistration.event.dojo', 'eventRegistration.member.dojo']);

        if ($request->has('event_id')) {
            $query->whereHas('eventRegistration', function($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        if ($request->has('dojo_id')) {
            $query->whereHas('eventRegistration.event', function($q) use ($request) {
                $q->where('dojo_id', $request->dojo_id);
            });
        }

        $certificates = $query->orderBy('issued_date', 'desc')->paginate(20);
        $events = Event::with('dojo')->get();
        $dojos = Dojo::all();

        return view('admin.events.certificates', compact('certificates', 'events', 'dojos'));
    }

    // Riwayat Event
    public function history(Request $request)
    {
        $query = Event::with(['dojo']);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $events = $query->orderBy('event_date', 'desc')->paginate(20);
        $dojos = Dojo::all();

        return view('admin.events.history', compact('events', 'dojos'));
    }
}
