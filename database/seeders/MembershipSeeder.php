<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membership;
use App\Models\Dojo;
use Carbon\Carbon;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        $membershipTypes = [
            [
                'name' => 'Monthly Membership',
                'description' => 'Full access for one month',
                'price' => 500000,
                'duration_days' => 30,
                'is_active' => true,
            ],
            [
                'name' => '3-Month Membership',
                'description' => 'Full access for 3 months (10% discount)',
                'price' => 1350000,
                'duration_days' => 90,
                'is_active' => true,
            ],
            [
                'name' => '6-Month Membership',
                'description' => 'Full access for 6 months (15% discount)',
                'price' => 2550000,
                'duration_days' => 180,
                'is_active' => true,
            ],
            [
                'name' => 'Annual Membership',
                'description' => 'Full access for one year (20% discount)',
                'price' => 4800000,
                'duration_days' => 365,
                'is_active' => true,
            ],
            [
                'name' => 'Drop-in Class',
                'description' => 'Single class session',
                'price' => 50000,
                'duration_days' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($dojos as $dojo) {
            foreach ($membershipTypes as $membership) {
                Membership::updateOrCreate(
                    [
                        'dojo_id' => $dojo->id,
                        'name' => $membership['name'],
                    ],
                    [
                        'description' => $membership['description'],
                        'price' => $membership['price'],
                        'duration_days' => $membership['duration_days'],
                        'is_active' => $membership['is_active'],
                    ]
                );
            }
        }

        $this->command->info('Membership plans seeded successfully!');
    }
}

