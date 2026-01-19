<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Instructor;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
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

        $query = Event::where(function($q) use ($dojoId) {
                $q->where('dojo_id', $dojoId)
                  ->orWhereNull('dojo_id'); // Include events for all dojos
            })
            ->with(['dojo', 'registrations.member']);

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

        return view('coach.events.index', compact('events', 'instructor'));
    }

    public function show(Event $event)
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

        $event->load(['dojo', 'registrations.member']);

        return view('coach.events.show', compact('event', 'instructor'));
    }
}

