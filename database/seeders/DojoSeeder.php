<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dojo;

class DojoSeeder extends Seeder
{
    public function run(): void
    {
        Dojo::create([
            'name' => 'Sample Dojo',
            'description' => 'A sample martial arts dojo',
            'address' => '123 Main Street',
            'phone' => '555-0100',
            'email' => 'info@sampledojo.com',
            'website_url' => 'https://sampledojo.com',
        ]);
    }
}
