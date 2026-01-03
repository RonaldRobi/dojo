<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\Dojo;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function getSettings()
    {
        $settings = SystemSetting::orderBy('category')->orderBy('key')->get();
        $dojos = Dojo::with('profile')->get();
        return view('admin.system.settings', compact('settings', 'dojos'));
    }

    public function updateSetting(Request $request, $key)
    {
        // Handle bulk update
        if ($key === 'bulk' && $request->has('settings')) {
            foreach ($request->settings as $settingKey => $settingData) {
                $value = $settingData['value'] ?? '';
                if (isset($settingData['type']) && $settingData['type'] === 'boolean') {
                    $value = $value ? '1' : '0';
                }
                
                SystemSetting::updateOrCreate(
                    ['key' => $settingKey],
                    [
                        'value' => $value,
                        'type' => $settingData['type'] ?? 'string',
                    ]
                );
            }
            return redirect()->back()->with('success', 'Settings updated successfully.');
        }

        // Handle single setting update
        $validated = $request->validate([
            'value' => 'required',
        ]);

        $setting = SystemSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $validated['value'],
                'type' => $request->input('type', 'string'),
            ]
        );

        return redirect()->back()->with('success', 'Setting updated successfully.');
    }

    public function getAllDojos()
    {
        $dojos = Dojo::with('profile')->get();
        return view('admin.system.dojos', compact('dojos'));
    }

    // Master Data Sistem
    public function masterData(Request $request)
    {
        $query = \App\Models\MasterData::with('dojo');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $masterData = $query->orderBy('type')->orderBy('order')->paginate(20);
        $types = ['style', 'level', 'belt', 'category'];

        return view('admin.system.master-data', compact('masterData', 'types'));
    }

    // Integrasi WhatsApp
    public function whatsappIntegration(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'whatsapp_api_key' => 'nullable|string',
                'whatsapp_api_url' => 'nullable|url',
                'whatsapp_phone_number' => 'nullable|string',
                'whatsapp_enabled' => 'boolean',
            ]);

            foreach ($validated as $key => $value) {
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'type' => is_bool($value) ? 'boolean' : 'string']
                );
            }

            return redirect()->route('admin.settings.whatsapp')
                ->with('success', 'Pengaturan WhatsApp berhasil disimpan.');
        }

        $settings = SystemSetting::whereIn('key', [
            'whatsapp_api_key',
            'whatsapp_api_url',
            'whatsapp_phone_number',
            'whatsapp_enabled',
        ])->get()->keyBy('key');

        return view('admin.system.whatsapp', compact('settings'));
    }

    // Integrasi Email
    public function emailIntegration(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'mail_mailer' => 'required|string',
                'mail_host' => 'nullable|string',
                'mail_port' => 'nullable|integer',
                'mail_username' => 'nullable|string',
                'mail_password' => 'nullable|string',
                'mail_encryption' => 'nullable|string',
                'mail_from_address' => 'nullable|email',
                'mail_from_name' => 'nullable|string',
            ]);

            foreach ($validated as $key => $value) {
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value, 'type' => is_numeric($value) ? 'integer' : 'string']
                );
            }

            return redirect()->route('admin.settings.email')
                ->with('success', 'Pengaturan Email berhasil disimpan.');
        }

        $settings = SystemSetting::where('key', 'like', 'mail_%')->get()->keyBy('key');

        return view('admin.system.email', compact('settings'));
    }
}
