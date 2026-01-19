<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class DefaultPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPricing = [
            [
                'key' => 'pricing_monthly_fees',
                'value' => '120',
                'type' => 'numeric',
                'description' => 'Monthly Fees',
                'category' => 'pricing',
            ],
            [
                'key' => 'pricing_annual_registration_fee',
                'value' => '130',
                'type' => 'numeric',
                'description' => 'Annual/Registration Fee',
                'category' => 'pricing',
            ],
            [
                'key' => 'pricing_uniform',
                'value' => '120',
                'type' => 'numeric',
                'description' => 'Uniform',
                'category' => 'pricing',
            ],
        ];

        foreach ($defaultPricing as $pricing) {
            SystemSetting::updateOrCreate(
                ['key' => $pricing['key']],
                $pricing
            );
        }

        $this->command->info('Default pricing settings created successfully!');
    }
}
