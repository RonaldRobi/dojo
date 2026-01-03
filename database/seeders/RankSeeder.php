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
                    ['description' => 'Minimum attendance: 20 classes', 'type' => 'attendance'],
                    ['description' => 'Basic kata demonstration', 'type' => 'kata'],
                    ['description' => 'Sparring skills assessment', 'type' => 'sparring'],
                ];

                foreach ($requirements as $req) {
                    RankRequirement::updateOrCreate(
                        [
                            'rank_id' => $rank->id,
                            'requirement_type' => $req['type'],
                            'description' => $req['description'],
                        ],
                        [
                            'requirement_value' => '20', // Default value
                        ]
                    );
                }
            }
        }

        // Create national ranks (null dojo_id)
        $nationalRanks = [
            ['name' => 'National White Belt', 'level' => 1, 'order' => 1, 'color' => '#FFFFFF'],
            ['name' => 'National Yellow Belt', 'level' => 2, 'order' => 2, 'color' => '#FFFF00'],
            ['name' => 'National Black Belt 1st Dan', 'level' => 8, 'order' => 8, 'color' => '#000000'],
        ];

        foreach ($nationalRanks as $rankData) {
            $rank = Rank::updateOrCreate(
                [
                    'dojo_id' => null,
                    'name' => $rankData['name'],
                ],
                [
                    'level' => $rankData['level'],
                    'order' => $rankData['order'],
                    'color' => $rankData['color'],
                ]
            );
        }

        $this->command->info('Ranks and rank requirements seeded successfully!');
    }
}

