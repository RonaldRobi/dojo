<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">WhatsApp Integration</h2>
            <p class="text-sm text-gray-500 mt-1">Configure WhatsApp integration settings</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">WhatsApp Settings</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.settings.whatsapp.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                    <input type="text" name="whatsapp_api_key" value="{{ $settings['whatsapp_api_key']->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">API URL</label>
                    <input type="url" name="whatsapp_api_url" value="{{ $settings['whatsapp_api_url']->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="whatsapp_phone_number" value="{{ $settings['whatsapp_phone_number']->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="whatsapp_enabled" value="1" {{ ($settings['whatsapp_enabled']->value ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600">
                        <span class="ml-2 text-sm text-gray-700">Enable WhatsApp</span>
                    </label>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

