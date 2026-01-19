<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        // Get pricing settings with defaults
        $pricing = [
            'monthly_fees' => $this->getSetting('pricing_monthly_fees', '120'),
            'annual_registration_fee' => $this->getSetting('pricing_annual_registration_fee', '130'),
            'uniform' => $this->getSetting('pricing_uniform', '120'),
        ];

        return view('admin.pricing.index', compact('pricing'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'monthly_fees' => 'required|numeric|min:0',
            'annual_registration_fee' => 'required|numeric|min:0',
            'uniform' => 'required|numeric|min:0',
        ]);

        // Update or create each setting
        $this->updateSetting('pricing_monthly_fees', $validated['monthly_fees'], 'Monthly Fees');
        $this->updateSetting('pricing_annual_registration_fee', $validated['annual_registration_fee'], 'Annual/Registration Fee');
        $this->updateSetting('pricing_uniform', $validated['uniform'], 'Uniform');

        return redirect()->route('admin.pricing.index')->with('success', 'Pricing updated successfully!');
    }

    private function getSetting($key, $default = null)
    {
        $setting = SystemSetting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    private function updateSetting($key, $value, $description)
    {
        SystemSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => 'numeric',
                'description' => $description,
                'category' => 'pricing',
            ]
        );
    }
}

