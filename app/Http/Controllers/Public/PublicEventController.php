<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('is_public', true)
            ->where('is_active', true)
            ->where('event_date', '>=', now())
            ->with('dojo')
            ->orderBy('event_date', 'asc');

        if ($request->has('dojo_id')) {
            $query->where('dojo_id', $request->dojo_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $events = $query->paginate(12);
        
        if (request()->wantsJson()) {
            return response()->json($events);
        }

        return view('public.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        if (!$event->is_public) {
            abort(404);
        }

        $event->load(['dojo', 'registrations']);
        
        if (request()->wantsJson()) {
            return response()->json($event);
        }
        
        return view('public.events.show', compact('event'));
    }
}
