<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'Dojo Management System',
                'type' => 'string',
                'description' => 'Application Name',
                'category' => 'General',
            ],
            [
                'key' => 'app_version',
                'value' => '1.0.0',
                'type' => 'string',
                'description' => 'Application Version',
                'category' => 'General',
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Maintenance Mode',
                'category' => 'General',
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'string',
                'description' => 'System Timezone',
                'category' => 'General',
            ],

            // Notification Settings
            [
                'key' => 'email_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable Email Notifications',
                'category' => 'Notifications',
            ],
            [
                'key' => 'sms_notifications_enabled',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Enable SMS Notifications',
                'category' => 'Notifications',
            ],
            [
                'key' => 'push_notifications_enabled',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Enable Push Notifications',
                'category' => 'Notifications',
            ],
            [
                'key' => 'notification_batch_size',
                'value' => '100',
                'type' => 'integer',
                'description' => 'Notification Batch Size',
                'category' => 'Notifications',
            ],

            // Payment Settings
            [
                'key' => 'payment_gateway',
                'value' => 'midtrans',
                'type' => 'string',
                'description' => 'Payment Gateway Provider',
                'category' => 'Payment',
            ],
            [
                'key' => 'currency',
                'value' => 'IDR',
                'type' => 'string',
                'description' => 'Currency Code',
                'category' => 'Payment',
            ],
            [
                'key' => 'auto_generate_invoice',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'Auto Generate Invoice',
                'category' => 'Payment',
            ],
            [
                'key' => 'invoice_due_days',
                'value' => '7',
                'type' => 'integer',
                'description' => 'Invoice Due Days',
                'category' => 'Payment',
            ],
            [
                'key' => 'registration_fee',
                'value' => '150',
                'type' => 'decimal',
                'description' => 'Child Registration Fee (RM)',
                'category' => 'Payment',
            ],
            [
                'key' => 'uniform_price',
                'value' => '100',
                'type' => 'decimal',
                'description' => 'Uniform Price (RM)',
                'category' => 'Payment',
            ],

            // Attendance Settings
            [
                'key' => 'attendance_auto_checkin',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Enable Auto Check-in',
                'category' => 'Attendance',
            ],
            [
                'key' => 'attendance_grace_period',
                'value' => '15',
                'type' => 'integer',
                'description' => 'Attendance Grace Period (minutes)',
                'category' => 'Attendance',
            ],
            [
                'key' => 'max_absence_days',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Max Consecutive Absence Days',
                'category' => 'Attendance',
            ],

            // Security Settings
            [
                'key' => 'password_min_length',
                'value' => '8',
                'type' => 'integer',
                'description' => 'Minimum Password Length',
                'category' => 'Security',
            ],
            [
                'key' => 'session_timeout',
                'value' => '120',
                'type' => 'integer',
                'description' => 'Session Timeout (minutes)',
                'category' => 'Security',
            ],
            [
                'key' => 'max_login_attempts',
                'value' => '5',
                'type' => 'integer',
                'description' => 'Max Login Attempts',
                'category' => 'Security',
            ],
            [
                'key' => 'two_factor_auth_enabled',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Enable Two-Factor Authentication',
                'category' => 'Security',
            ],

            // Integration Settings
            [
                'key' => 'whatsapp_api_key',
                'value' => '',
                'type' => 'string',
                'description' => 'WhatsApp API Key',
                'category' => 'Integration',
            ],
            [
                'key' => 'whatsapp_api_url',
                'value' => '',
                'type' => 'string',
                'description' => 'WhatsApp API URL',
                'category' => 'Integration',
            ],
            [
                'key' => 'whatsapp_enabled',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Enable WhatsApp Integration',
                'category' => 'Integration',
            ],

            // Display Settings
            [
                'key' => 'items_per_page',
                'value' => '20',
                'type' => 'integer',
                'description' => 'Items Per Page',
                'category' => 'Display',
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'string',
                'description' => 'Date Format',
                'category' => 'Display',
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'string',
                'description' => 'Time Format',
                'category' => 'Display',
            ],
            [
                'key' => 'theme_color',
                'value' => '{"primary": "#9333ea", "secondary": "#6366f1"}',
                'type' => 'json',
                'description' => 'Theme Colors',
                'category' => 'Display',
            ],
            [
                'key' => 'dark_mode',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Enable Dark Mode',
                'category' => 'Display',
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
