<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use App\Models\MemberRank;
use App\Models\MemberMembership;
use App\Models\Rank;
use App\Models\Membership;
use App\Models\Dojo;
use App\Models\User;
use App\Services\RoleService;
use Carbon\Carbon;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        $roleService = app(RoleService::class);

        foreach ($dojos as $dojoIndex => $dojo) {
            $ranks = Rank::where('dojo_id', $dojo->id)->orderBy('level', 'asc')->get();
            $memberships = Membership::where('dojo_id', $dojo->id)->where('is_active', true)->get();
            
            if ($ranks->isEmpty()) {
                continue;
            }

            $firstRank = $ranks->first();
            $monthlyMembership = $memberships->where('duration_days', 30)->first();

            // Create 15 members per dojo
            for ($i = 1; $i <= 15; $i++) {
                $memberUser = User::updateOrCreate(
                    ['email' => 'member' . $dojoIndex . $i . '@dojo.com'],
                    [
                        'name' => 'Student ' . str_pad($i, 2, '0', STR_PAD_LEFT) . ' - ' . $dojo->name,
                        'password' => \Illuminate\Support\Facades\Hash::make('password'),
                        'dojo_id' => $dojo->id,
                        'status' => 'active',
                        'password_changed_at' => now(),
                    ]
                );

                $roleService->assignRole($memberUser, 'student', $dojo->id);

                // Determine rank based on index (mix of ranks)
                $rankLevel = min($i % 8 + 1, $ranks->count());
                $currentRank = $ranks->where('level', $rankLevel)->first() ?? $firstRank;
                $joinDate = Carbon::now()->subMonths(rand(1, 24));

                $member = Member::updateOrCreate(
                    [
                        'dojo_id' => $dojo->id,
                        'user_id' => $memberUser->id,
                    ],
                    [
                        'name' => $memberUser->name,
                        'birth_date' => Carbon::now()->subYears(rand(8, 40)),
                        'gender' => ['male', 'female'][rand(0, 1)],
                        'phone' => '0812' . str_pad($dojoIndex * 100 + $i, 8, '0', STR_PAD_LEFT),
                        'address' => 'Address ' . $i . ', ' . $dojo->name,
                        'status' => ['active', 'active', 'active', 'active', 'leave'][rand(0, 4)],
                        'join_date' => $joinDate,
                        'current_level' => 'Level ' . $rankLevel,
                        'current_belt_id' => $currentRank->id,
                        'style' => 'Karate',
                        'medical_notes' => $i % 5 === 0 ? 'No known allergies' : null,
                        'waiver_signed_at' => $joinDate,
                    ]
                );

                // Add member rank history
                MemberRank::updateOrCreate(
                    [
                        'member_id' => $member->id,
                        'rank_id' => $currentRank->id,
                    ],
                    [
                        'achieved_at' => $joinDate->copy()->addDays(rand(30, 180)),
                        'awarded_by_instructor_id' => $dojo->instructors()->first()?->id,
                        'certificate_url' => null,
                        'grading_event_id' => null,
                    ]
                );

                // Add membership if member is active
                if ($member->status === 'active' && $monthlyMembership) {
                    $membershipStart = Carbon::now()->subDays(rand(0, 30));
                    MemberMembership::updateOrCreate(
                        [
                            'member_id' => $member->id,
                            'membership_id' => $monthlyMembership->id,
                            'start_date' => $membershipStart,
                        ],
                        [
                            'end_date' => $membershipStart->copy()->addDays($monthlyMembership->duration_days),
                            'status' => $membershipStart->copy()->addDays($monthlyMembership->duration_days)->isFuture() ? 'active' : 'expired',
                            'auto_renew' => true,
                        ]
                    );
                }
            }
        }

        $this->command->info('Members, ranks, and memberships seeded successfully!');
    }
}

