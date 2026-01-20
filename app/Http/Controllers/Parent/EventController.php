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
        
        // Get ALL children from ALL dojos
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');
        
        $children = Member::whereIn('id', $memberIds)
            ->with(['dojo'])
            ->get();

        if ($children->isEmpty()) {
            return view('parent.events.index', ['children' => $children, 'allEvents' => collect()]);
        }

        // Get all dojos where children are enrolled
        $dojoIds = $children->pluck('dojo_id')->unique();

        // Get all upcoming events from all children's dojos + events for "all dojos" (dojo_id = null)
        $allEvents = \App\Models\Event::where(function($query) use ($dojoIds) {
                $query->whereIn('dojo_id', $dojoIds)
                      ->orWhereNull('dojo_id'); // Include "All Dojos" events
            })
            ->where('event_date', '>=', now())
            ->where('is_active', true)
            ->with(['dojo'])
            ->orderBy('event_date')
            ->get();

        // Get all registrations for all children
        $registrations = \App\Models\EventRegistration::whereIn('member_id', $memberIds)
            ->with(['event', 'member'])
            ->get();

        // Mark which events each child is already registered for
        foreach ($allEvents as $event) {
            $event->registered_children = $registrations->where('event_id', $event->id)->pluck('member_id')->toArray();
        }

        return view('parent.events.index', compact('children', 'allEvents', 'registrations'));
    }

    public function show($eventId)
    {
        $user = auth()->user();

        $event = \App\Models\Event::findOrFail($eventId);

        // Get ALL children from ALL dojos
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');
        
        $children = Member::whereIn('id', $memberIds)
            ->with(['dojo', 'currentBelt'])
            ->get();

        // Get registrations for this event
        $registrations = \App\Models\EventRegistration::where('event_id', $eventId)
            ->whereIn('member_id', $memberIds)
            ->with(['member', 'invoice'])
            ->get();

        return view('parent.events.show', compact('event', 'children', 'registrations'));
    }

    public function register(Request $request, $eventId)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        $user = auth()->user();
        $event = \App\Models\Event::findOrFail($eventId);

        // Verify the member belongs to this parent
        $memberIds = \App\Models\ParentStudent::where('parent_user_id', $user->id)
            ->pluck('member_id');

        if (!$memberIds->contains($request->member_id)) {
            return back()->with('error', 'You do not have permission to register this child.');
        }

        // Check if already registered
        $existingRegistration = \App\Models\EventRegistration::where('event_id', $eventId)
            ->where('member_id', $request->member_id)
            ->first();

        if ($existingRegistration) {
            return back()->with('error', 'This child is already registered for this event.');
        }

        // Get member to get their dojo_id
        $member = Member::findOrFail($request->member_id);

        // Create invoice first - use member's dojo_id, not event's dojo_id (event might be for "all dojos")
        $invoice = \App\Models\Invoice::create([
            'dojo_id' => $member->dojo_id,
            'member_id' => $request->member_id,
            'invoice_number' => 'INV-EVT-' . time() . '-' . $request->member_id,
            'type' => 'event', // Valid enum values: 'membership', 'class', 'event', 'private'
            'amount' => $event->registration_fee ?? 0,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => $event->registration_fee ?? 0,
            'invoice_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'pending',
            'description' => 'Event Registration: ' . $event->name . ' for ' . $member->name,
            'paid_at' => null,
        ]);

        // Create event registration
        $registration = \App\Models\EventRegistration::create([
            'event_id' => $eventId,
            'member_id' => $request->member_id,
            'registered_at' => now(),
            'status' => 'confirmed', // Valid enum values: 'confirmed', 'cancelled'
            'payment_status' => 'pending', // Valid enum values: 'pending', 'paid', 'refunded'
            'payment_invoice_id' => $invoice->id,
            'registered_by_user_id' => $user->id,
        ]);

        return redirect()->route('parent.events.show', $eventId)
            ->with('success', 'Child successfully registered for the event. Please complete the payment.');
    }
}

