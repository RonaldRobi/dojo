<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rank;
use App\Models\RankRequirement;
use App\Models\Dojo;
use Carbon\Carbon;

class RankSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();

        if ($dojos->isEmpty()) {
            $this->command->warn('No dojos found. Please run DojoSeeder first.');
            return;
        }

        foreach ($dojos as $dojo) {
            // Create ranks for each dojo (Karate style)
            $ranks = [
                ['name' => 'White Belt', 'level' => 1, 'order' => 1, 'color' => '#FFFFFF'],
                ['name' => 'Yellow Belt', 'level' => 2, 'order' => 2, 'color' => '#FFFF00'],
                ['name' => 'Orange Belt', 'level' => 3, 'order' => 3, 'color' => '#FFA500'],
                ['name' => 'Green Belt', 'level' => 4, 'order' => 4, 'color' => '#008000'],
                ['name' => 'Blue Belt', 'level' => 5, 'order' => 5, 'color' => '#0000FF'],
                ['name' => 'Purple Belt', 'level' => 6, 'order' => 6, 'color' => '#800080'],
                ['name' => 'Brown Belt', 'level' => 7, 'order' => 7, 'color' => '#8B4513'],
                ['name' => 'Black Belt 1st Dan', 'level' => 8, 'order' => 8, 'color' => '#000000'],
            ];

            foreach ($ranks as $rankData) {
                $rank = Rank::updateOrCreate(
                    [
                        'dojo_id' => $dojo->id,
                        'name' => $rankData['name'],
                    ],
                    [
                        'level' => $rankData['level'],
                        'order' => $rankData['order'],
                        'color' => $rankData['color'],
                    ]
                );

                // Add requirements for each rank
                $requirements = [
                    [
                        'type' => 'attendance_min',
                        'value' => $rankData['level'] * 10,  // Higher belt = more attendance required
                        'description' => 'Minimum number of classes attended required for promotion',
                    ],
                    [
                        'type' => 'exam_required',
                        'value' => 'yes',
                        'description' => 'Must pass practical examination demonstrating techniques',
                    ],
                    [
                        'type' => 'recommendation_required',
                        'value' => 'yes',
                        'description' => 'Requires instructor recommendation for promotion',
                    ],
                ];

                foreach ($requirements as $req) {
                    RankRequirement::updateOrCreate(
                        [
                            'rank_id' => $rank->id,
                            'requirement_type' => $req['type'],
                        ],
                        [
                            'requirement_value' => (string)$req['value'],
                            'description' => $req['description'],
                        ]
                    );
                }
            }
        }

        $this->command->info('Ranks and rank requirements seeded successfully!');
    }
}

