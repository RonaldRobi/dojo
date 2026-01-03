<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instructor;
use App\Models\InstructorCertification;
use App\Models\Dojo;
use App\Models\User;
use App\Services\RoleService;
use Carbon\Carbon;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        $roleService = app(RoleService::class);

        foreach ($dojos as $index => $dojo) {
            // Create main instructor
            $instructorUser = User::updateOrCreate(
                ['email' => 'instructor' . ($index + 1) . '@dojo.com'],
                [
                    'name' => 'Instructor ' . ($index + 1) . ' - ' . $dojo->name,
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'dojo_id' => $dojo->id,
                    'status' => 'active',
                    'password_changed_at' => now(),
                ]
            );

            $roleService->assignRole($instructorUser, 'coach', $dojo->id);

            $instructor = Instructor::updateOrCreate(
                [
                    'dojo_id' => $dojo->id,
                    'user_id' => $instructorUser->id,
                ],
                [
                    'name' => $instructorUser->name,
                    'email' => $instructorUser->email,
                    'phone' => '0812' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                    'specialization' => 'Karate',
                    'bio' => 'Experienced martial arts instructor with over 10 years of teaching experience.',
                    'hire_date' => Carbon::now()->subYears(2),
                    'status' => 'active',
                    'certification_level' => 'Black Belt 3rd Dan',
                ]
            );

            // Add certifications
            InstructorCertification::updateOrCreate(
                [
                    'instructor_id' => $instructor->id,
                    'certification_name' => 'Black Belt 3rd Dan',
                ],
                [
                    'issued_by' => 'National Karate Association',
                    'issued_date' => Carbon::now()->subYears(1),
                    'expiry_date' => null,
                    'certificate_document_path' => null,
                ]
            );

            InstructorCertification::updateOrCreate(
                [
                    'instructor_id' => $instructor->id,
                    'certification_name' => 'First Aid & CPR',
                ],
                [
                    'issued_by' => 'Red Cross',
                    'issued_date' => Carbon::now()->subMonths(6),
                    'expiry_date' => Carbon::now()->addMonths(18),
                    'certificate_document_path' => null,
                ]
            );

            // Create assistant instructor
            if ($index === 0) {
                $assistantUser = User::updateOrCreate(
                    ['email' => 'assistant@dojo.com'],
                    [
                        'name' => 'Assistant Instructor - ' . $dojo->name,
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'dojo_id' => $dojo->id,
                        'status' => 'active',
                        'password_changed_at' => now(),
                    ]
                );

                $roleService->assignRole($assistantUser, 'coach', $dojo->id);

                $assistant = Instructor::updateOrCreate(
                    [
                        'dojo_id' => $dojo->id,
                        'user_id' => $assistantUser->id,
                    ],
                    [
                        'name' => $assistantUser->name,
                        'email' => $assistantUser->email,
                        'phone' => '081399999999',
                        'specialization' => 'Karate',
                        'bio' => 'Junior instructor assisting with classes.',
                        'hire_date' => Carbon::now()->subMonths(6),
                        'status' => 'active',
                        'certification_level' => 'Black Belt 1st Dan',
                    ]
                );

                InstructorCertification::updateOrCreate(
                    [
                        'instructor_id' => $assistant->id,
                        'certification_name' => 'Black Belt 1st Dan',
                    ],
                    [
                        'issued_by' => 'National Karate Association',
                        'issued_date' => Carbon::now()->subMonths(8),
                        'expiry_date' => null,
                        'certificate_document_path' => null,
                    ]
                );
            }
        }

        $this->command->info('Instructors and certifications seeded successfully!');
    }
}

