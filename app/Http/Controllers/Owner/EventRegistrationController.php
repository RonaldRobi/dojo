<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;

class EventRegistrationController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'member_id' => 'required|exists:members,id',
            'notes' => 'nullable|string',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        // Check capacity
        if ($event->capacity) {
            $registeredCount = $event->registrations()->where('status', 'confirmed')->count();
            if ($registeredCount >= $event->capacity) {
                return response()->json(['error' => 'Event is full'], 400);
            }
        }

        $registration = EventRegistration::create([
            'event_id' => $validated['event_id'],
            'member_id' => $validated['member_id'],
            'registered_at' => now(),
            'status' => 'confirmed',
            'payment_status' => $event->registration_fee > 0 ? 'pending' : 'paid',
            'registered_by_user_id' => auth()->id(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return response()->json($registration, 201);
    }

    public function cancel(EventRegistration $registration)
    {
        $registration->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Registration cancelled successfully']);
    }

    public function index(Request $request)
    {
        $dojoId = currentDojo();
        
        $query = EventRegistration::whereHas('event', function($q) use ($dojoId) {
            $q->where('dojo_id', $dojoId);
        })->with(['event', 'member']);

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->paginate(50);

        return response()->json($registrations);
    }
}
