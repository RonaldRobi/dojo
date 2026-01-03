<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curriculum;
use App\Models\Rank;
use Carbon\Carbon;

class CurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $ranks = Rank::all();

        if ($ranks->isEmpty()) {
            $this->command->warn('No ranks found. Please run RankSeeder first.');
            return;
        }

        // Curriculum items by rank level
        $curriculumMap = [
            1 => [
                ['skill_name' => 'Basic Stance (Heisoku Dachi)', 'description' => 'Proper standing position', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Punch (Oi Zuki)', 'description' => 'Basic straight punch', 'order' => 2, 'is_required' => true],
                ['skill_name' => 'Front Kick (Mae Geri)', 'description' => 'Basic front kick', 'order' => 3, 'is_required' => true],
            ],
            2 => [
                ['skill_name' => 'Kata: Heian Shodan', 'description' => 'First basic kata', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Side Kick (Yoko Geri)', 'description' => 'Side kick technique', 'order' => 2, 'is_required' => true],
                ['skill_name' => 'Block: Gedan Barai', 'description' => 'Low block', 'order' => 3, 'is_required' => true],
            ],
            3 => [
                ['skill_name' => 'Kata: Heian Nidan', 'description' => 'Second basic kata', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Roundhouse Kick (Mawashi Geri)', 'description' => 'Roundhouse kick technique', 'order' => 2, 'is_required' => true],
                ['skill_name' => 'Block: Age Uke', 'description' => 'Rising block', 'order' => 3, 'is_required' => true],
            ],
            4 => [
                ['skill_name' => 'Kata: Heian Sandan', 'description' => 'Third basic kata', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Back Kick (Ushiro Geri)', 'description' => 'Back kick technique', 'order' => 2, 'is_required' => false],
                ['skill_name' => 'Kumite Basics', 'description' => 'Basic sparring techniques', 'order' => 3, 'is_required' => true],
            ],
            5 => [
                ['skill_name' => 'Kata: Heian Yondan', 'description' => 'Fourth basic kata', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Kata: Heian Godan', 'description' => 'Fifth basic kata', 'order' => 2, 'is_required' => true],
                ['skill_name' => 'Advanced Kumite', 'description' => 'Advanced sparring techniques', 'order' => 3, 'is_required' => true],
            ],
            6 => [
                ['skill_name' => 'Kata: Tekki Shodan', 'description' => 'Iron Horse kata', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Kata: Bassai Dai', 'description' => 'To penetrate a fortress', 'order' => 2, 'is_required' => true],
                ['skill_name' => 'Advanced Techniques', 'description' => 'Complex techniques', 'order' => 3, 'is_required' => false],
            ],
            7 => [
                ['skill_name' => 'Kata: Kanku Dai', 'description' => 'To view the sky', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Kata: Jion', 'description' => 'Temple sound', 'order' => 2, 'is_required' => true],
                ['skill_name' => 'Master Level Techniques', 'description' => 'Master level skills', 'order' => 3, 'is_required' => true],
            ],
            8 => [
                ['skill_name' => 'Kata: Enpi', 'description' => 'Flying swallow', 'order' => 1, 'is_required' => true],
                ['skill_name' => 'Kata: Jitte', 'description' => 'Ten hands', 'order' => 2, 'is_required' => true],
                ['skill_name' => 'Kata: Gankaku', 'description' => 'Crane on a rock', 'order' => 3, 'is_required' => true],
                ['skill_name' => 'Teaching Methodology', 'description' => 'Instructor training', 'order' => 4, 'is_required' => true],
            ],
        ];

        foreach ($ranks as $rank) {
            $level = $rank->level;
            if (isset($curriculumMap[$level])) {
                foreach ($curriculumMap[$level] as $curriculum) {
                    Curriculum::updateOrCreate(
                        [
                            'rank_id' => $rank->id,
                            'skill_name' => $curriculum['skill_name'],
                        ],
                        [
                            'description' => $curriculum['description'],
                            'order' => $curriculum['order'],
                            'is_required' => $curriculum['is_required'],
                        ]
                    );
                }
            }
        }

        $this->command->info('Curriculum data seeded successfully!');
    }
}

