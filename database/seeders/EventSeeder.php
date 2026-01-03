<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventCertificate;
use App\Models\Dojo;
use App\Models\Member;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        foreach ($dojos as $dojo) {
            $members = Member::where('dojo_id', $dojo->id)->where('status', 'active')->get();

            // Create upcoming events
            $upcomingEvents = [
                [
                    'name' => 'Monthly Grading Test',
                    'type' => 'grading',
                    'description' => 'Regular monthly belt promotion test',
                    'event_date' => Carbon::now()->addWeeks(2),
                    'registration_deadline' => Carbon::now()->addWeeks(1),
                    'location' => $dojo->name . ' Main Hall',
                    'capacity' => 50,
                    'registration_fee' => 100000,
                    'is_active' => true,
                    'is_public' => false,
                ],
                [
                    'name' => 'Karate Tournament 2024',
                    'type' => 'tournament',
                    'description' => 'Annual karate championship tournament',
                    'event_date' => Carbon::now()->addMonths(2),
                    'registration_deadline' => Carbon::now()->addMonth(),
                    'location' => 'City Sports Complex',
                    'capacity' => 200,
                    'registration_fee' => 250000,
                    'is_active' => true,
                    'is_public' => true,
                ],
                [
                    'name' => 'Seminar: Advanced Kata Techniques',
                    'type' => 'seminar',
                    'description' => 'Learn advanced kata from master instructor',
                    'event_date' => Carbon::now()->addWeeks(4),
                    'registration_deadline' => Carbon::now()->addWeeks(3),
                    'location' => $dojo->name . ' Training Hall',
                    'capacity' => 30,
                    'registration_fee' => 150000,
                    'is_active' => true,
                    'is_public' => true,
                ],
            ];

            // Create past events
            $pastEvents = [
                [
                    'name' => 'Grading Test - ' . Carbon::now()->subMonth()->format('F Y'),
                    'type' => 'grading',
                    'description' => 'Monthly belt promotion test',
                    'event_date' => Carbon::now()->subMonth(),
                    'registration_deadline' => Carbon::now()->subMonth()->subWeek(),
                    'location' => $dojo->name . ' Main Hall',
                    'capacity' => 50,
                    'registration_fee' => 100000,
                    'is_active' => false,
                    'is_public' => false,
                ],
            ];

            $allEvents = array_merge($upcomingEvents, $pastEvents);

            foreach ($allEvents as $eventData) {
                $event = Event::updateOrCreate(
                    [
                        'dojo_id' => $dojo->id,
                        'name' => $eventData['name'],
                        'event_date' => $eventData['event_date'],
                    ],
                    $eventData
                );

                // Create registrations for some members
                if ($members->count() > 0) {
                    $registrationCount = min(rand(5, min(15, $members->count())), $event->capacity);
                    $selectedMembers = $members->random($registrationCount);

                    foreach ($selectedMembers as $member) {
                        $registration = EventRegistration::updateOrCreate(
                            [
                                'event_id' => $event->id,
                                'member_id' => $member->id,
                            ],
                            [
                                'registered_at' => $event->registration_deadline->copy()->subDays(rand(1, 7)),
                                'status' => $event->event_date->isPast() ? 'confirmed' : 'confirmed',
                                'payment_status' => 'paid',
                                'payment_invoice_id' => null,
                                'notes' => null,
                                'registered_by_user_id' => null,
                            ]
                        );

                        // Create certificate for completed grading events
                        if ($event->type === 'grading' && $event->event_date->isPast() && rand(0, 1)) {
                            EventCertificate::updateOrCreate(
                                [
                                    'event_registration_id' => $registration->id,
                                ],
                                [
                                    'certificate_url' => '/certificates/' . $registration->id . '.pdf',
                                    'issued_at' => $event->event_date->copy()->addDay(),
                                    'issued_by_instructor_id' => $event->dojo ? $event->dojo->instructors()->first()?->id : null,
                                    'certificate_type' => 'grading',
                                ]
                            );
                        }
                    }
                }
            }
        }

        // Create national event
        $nationalEvent = Event::updateOrCreate(
            [
                'dojo_id' => null,
                'name' => 'National Championship 2024',
            ],
            [
                'type' => 'tournament',
                'description' => 'National level karate championship',
                'event_date' => Carbon::now()->addMonths(3),
                'registration_deadline' => Carbon::now()->addMonths(2),
                'location' => 'Jakarta Convention Center',
                'capacity' => 500,
                'registration_fee' => 500000,
                'is_active' => true,
                'is_public' => true,
            ]
        );

        $this->command->info('Events, registrations, and certificates seeded successfully!');
    }
}

