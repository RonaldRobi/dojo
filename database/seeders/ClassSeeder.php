<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DojoClass;
use App\Models\ClassSchedule;
use App\Models\ClassEnrollment;
use App\Models\Dojo;
use App\Models\Instructor;
use App\Models\Member;
use Carbon\Carbon;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        foreach ($dojos as $dojo) {
            $instructors = Instructor::where('dojo_id', $dojo->id)->get();
            $members = Member::where('dojo_id', $dojo->id)->where('status', 'active')->get();

            if ($instructors->isEmpty()) {
                continue;
            }

            $instructor = $instructors->first();

            // Create classes
            $classes = [
                [
                    'name' => 'Beginner Karate',
                    'description' => 'Introduction to basic karate techniques',
                    'level_min' => 1,
                    'level_max' => 2,
                    'age_min' => 6,
                    'age_max' => 12,
                    'style' => 'Karate',
                    'capacity' => 20,
                    'is_active' => true,
                ],
                [
                    'name' => 'Intermediate Karate',
                    'description' => 'Intermediate level karate training',
                    'level_min' => 3,
                    'level_max' => 5,
                    'age_min' => 10,
                    'age_max' => 16,
                    'style' => 'Karate',
                    'capacity' => 18,
                    'is_active' => true,
                ],
                [
                    'name' => 'Advanced Karate',
                    'description' => 'Advanced karate techniques and kata',
                    'level_min' => 6,
                    'level_max' => 8,
                    'age_min' => 14,
                    'age_max' => 99,
                    'style' => 'Karate',
                    'capacity' => 15,
                    'is_active' => true,
                ],
                [
                    'name' => 'Adult Karate',
                    'description' => 'Karate classes for adults',
                    'level_min' => 1,
                    'level_max' => 8,
                    'age_min' => 18,
                    'age_max' => 99,
                    'style' => 'Karate',
                    'capacity' => 25,
                    'is_active' => true,
                ],
            ];

            foreach ($classes as $classData) {
                $dojoClass = DojoClass::updateOrCreate(
                    [
                        'dojo_id' => $dojo->id,
                        'name' => $classData['name'],
                    ],
                    $classData
                );

                // Create schedules for each class
                $scheduleDays = [
                    ['day' => 1, 'start' => '16:00', 'end' => '17:00'], // Monday
                    ['day' => 3, 'start' => '16:00', 'end' => '17:00'], // Wednesday
                    ['day' => 5, 'start' => '16:00', 'end' => '17:00'], // Friday
                ];

                // For adult class, use evening times
                if (str_contains($classData['name'], 'Adult')) {
                    $scheduleDays = [
                        ['day' => 1, 'start' => '19:00', 'end' => '20:30'], // Monday
                        ['day' => 3, 'start' => '19:00', 'end' => '20:30'], // Wednesday
                        ['day' => 5, 'start' => '19:00', 'end' => '20:30'], // Friday
                    ];
                }

                foreach ($scheduleDays as $scheduleData) {
                    $startTime = Carbon::createFromTimeString($scheduleData['start']);
                    $endTime = Carbon::createFromTimeString($scheduleData['end']);
                    
                    $schedule = ClassSchedule::updateOrCreate(
                        [
                            'class_id' => $dojoClass->id,
                            'day_of_week' => $scheduleData['day'],
                        ],
                        [
                            'instructor_id' => $instructor->id,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'location' => 'Main Dojo',
                            'is_active' => true,
                        ]
                    );

                    // Enroll some members to the schedule
                    $enrollmentCount = min(rand(5, 12), $members->count());
                    $selectedMembers = $members->random($enrollmentCount);

                    foreach ($selectedMembers as $member) {
                        ClassEnrollment::updateOrCreate(
                            [
                                'member_id' => $member->id,
                                'class_schedule_id' => $schedule->id,
                            ],
                            [
                                'enrolled_at' => Carbon::now()->subDays(rand(1, 90)),
                                'status' => 'active',
                            ]
                        );
                    }
                }
            }
        }

        $this->command->info('Classes, schedules, and enrollments seeded successfully!');
    }
}

