<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterData;
use App\Models\Dojo;
use Carbon\Carbon;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $dojos = Dojo::all();
        
        // Styles - Global (null dojo_id)
        $styles = [
            ['name' => 'Karate', 'code' => 'KAR', 'order' => 1, 'metadata' => ['color' => '#FF0000']],
            ['name' => 'Taekwondo', 'code' => 'TKD', 'order' => 2, 'metadata' => ['color' => '#0000FF']],
            ['name' => 'Judo', 'code' => 'JUD', 'order' => 3, 'metadata' => ['color' => '#FFFFFF']],
            ['name' => 'Aikido', 'code' => 'AIK', 'order' => 4, 'metadata' => ['color' => '#000080']],
            ['name' => 'Kung Fu', 'code' => 'KUN', 'order' => 5, 'metadata' => ['color' => '#FFD700']],
        ];

        foreach ($styles as $style) {
            MasterData::updateOrCreate(
                ['type' => 'style', 'code' => $style['code'], 'dojo_id' => null],
                [
                    'name' => $style['name'],
                    'order' => $style['order'],
                    'is_active' => true,
                    'metadata' => $style['metadata'],
                ]
            );
        }

        // Levels - Global
        $levels = [
            ['name' => 'Beginner', 'code' => 'BEG', 'order' => 1],
            ['name' => 'Intermediate', 'code' => 'INT', 'order' => 2],
            ['name' => 'Advanced', 'code' => 'ADV', 'order' => 3],
            ['name' => 'Expert', 'code' => 'EXP', 'order' => 4],
        ];

        foreach ($levels as $level) {
            MasterData::updateOrCreate(
                ['type' => 'level', 'code' => $level['code'], 'dojo_id' => null],
                [
                    'name' => $level['name'],
                    'order' => $level['order'],
                    'is_active' => true,
                ]
            );
        }

        // Belts - Global (for Karate/Taekwondo)
        $belts = [
            ['name' => 'White Belt', 'code' => 'WHT', 'order' => 1, 'metadata' => ['color' => '#FFFFFF']],
            ['name' => 'Yellow Belt', 'code' => 'YLW', 'order' => 2, 'metadata' => ['color' => '#FFFF00']],
            ['name' => 'Orange Belt', 'code' => 'ORG', 'order' => 3, 'metadata' => ['color' => '#FFA500']],
            ['name' => 'Green Belt', 'code' => 'GRN', 'order' => 4, 'metadata' => ['color' => '#008000']],
            ['name' => 'Blue Belt', 'code' => 'BLU', 'order' => 5, 'metadata' => ['color' => '#0000FF']],
            ['name' => 'Purple Belt', 'code' => 'PUR', 'order' => 6, 'metadata' => ['color' => '#800080']],
            ['name' => 'Brown Belt', 'code' => 'BRN', 'order' => 7, 'metadata' => ['color' => '#8B4513']],
            ['name' => 'Black Belt', 'code' => 'BLK', 'order' => 8, 'metadata' => ['color' => '#000000']],
        ];

        foreach ($belts as $belt) {
            MasterData::updateOrCreate(
                ['type' => 'belt', 'code' => $belt['code'], 'dojo_id' => null],
                [
                    'name' => $belt['name'],
                    'order' => $belt['order'],
                    'is_active' => true,
                    'metadata' => $belt['metadata'],
                ]
            );
        }

        // Dojo-specific styles (optional - add to first dojo)
        if ($dojos->count() > 0) {
            $firstDojo = $dojos->first();
            MasterData::updateOrCreate(
                ['type' => 'style', 'code' => 'KAR-DOJO', 'dojo_id' => $firstDojo->id],
                [
                    'name' => 'Karate - ' . $firstDojo->name,
                    'order' => 1,
                    'is_active' => true,
                    'metadata' => ['color' => '#FF0000'],
                ]
            );
        }

        $this->command->info('Master data (styles, levels, belts) seeded successfully!');
    }
}

