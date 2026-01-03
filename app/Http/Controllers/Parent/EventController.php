<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $childId = $request->input('child_id');
        
        $children = Member::whereHas('parents', function($q) use ($user, $dojoId) {
            $q->where('parent_user_id', $user->id)
              ->where('dojo_id', $dojoId);
        })->get();

        $selectedChild = $childId 
            ? $children->firstWhere('id', $childId)
            : $children->first();

        if (!$selectedChild) {
            return view('parent.events.index', ['children' => $children]);
        }

        $registrations = \App\Models\EventRegistration::where('member_id', $selectedChild->id)
            ->with(['event', 'certificate'])
            ->latest()
            ->get();

        $upcomingEvents = \App\Models\Event::where('dojo_id', $dojoId)
            ->where('event_date', '>=', now())
            ->where('is_public', true)
            ->latest('event_date')
            ->get();

        return view('parent.events.index', compact('children', 'selectedChild', 'registrations', 'upcomingEvents'));
    }

    public function show($eventId)
    {
        $user = auth()->user();
        $dojoId = currentDojo();

        $event = \App\Models\Event::where('id', $eventId)
            ->where('dojo_id', $dojoId)
            ->firstOrFail();

        $children = Member::whereHas('parents', function($q) use ($user, $dojoId) {
            $q->where('parent_user_id', $user->id)
              ->where('dojo_id', $dojoId);
        })->get();

        $registrations = \App\Models\EventRegistration::where('event_id', $eventId)
            ->whereIn('member_id', $children->pluck('id'))
            ->with('member')
            ->get();

        return view('parent.events.show', compact('event', 'children', 'registrations'));
    }
}

