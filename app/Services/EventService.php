<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Member;

class EventService
{
    public function registerMember(Event $event, Member $member): EventRegistration
    {
        // Check capacity
        if ($event->capacity) {
            $registeredCount = $event->registrations()
                ->where('status', 'confirmed')
                ->count();
            
            if ($registeredCount >= $event->capacity) {
                throw new \Exception('Event is full');
            }
        }

        // Check registration deadline
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            throw new \Exception('Registration deadline has passed');
        }

        return EventRegistration::create([
            'event_id' => $event->id,
            'member_id' => $member->id,
            'registered_at' => now(),
            'status' => 'confirmed',
            'payment_status' => $event->registration_fee > 0 ? 'pending' : 'paid',
            'registered_by_user_id' => auth()->id(),
        ]);
    }

    public function checkCapacity(Event $event): array
    {
        $registeredCount = $event->registrations()
            ->where('status', 'confirmed')
            ->count();

        return [
            'capacity' => $event->capacity,
            'registered' => $registeredCount,
            'available' => $event->capacity ? max(0, $event->capacity - $registeredCount) : null,
            'is_full' => $event->capacity ? $registeredCount >= $event->capacity : false,
        ];
    }
}

