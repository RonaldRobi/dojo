<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Settings Management</h2>
            <p class="text-sm text-gray-500 mt-1">Configure system settings here</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">System Settings</h3>
        </div>

        <form method="POST" action="{{ route('admin.system.settings.update', 'bulk') }}" class="p-6">
            @csrf
            @method('PUT')

            @php
                $settingsByCategory = $settings->groupBy(function($setting) {
                    return $setting->category ?? 'General';
                });
            @endphp

            @foreach($settingsByCategory as $category => $categorySettings)
                <div class="mb-8">
                    <h4 class="text-md font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-200">
                        {{ ucfirst($category) }}
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($categorySettings as $setting)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $setting->description ?? ucfirst(str_replace('_', ' ', $setting->key)) }}
                                </label>
                                @if($setting->type === 'boolean')
                                    <div class="flex items-center">
                                        <input type="hidden" name="settings[{{ $setting->key }}][value]" value="0">
                                        <input type="checkbox" 
                                               name="settings[{{ $setting->key }}][value]" 
                                               value="1"
                                               {{ $setting->value == '1' || $setting->value == 'true' ? 'checked' : '' }}
                                               @if($setting->key === 'dark_mode') 
                                                   id="darkModeToggle"
                                                   onchange="toggleDarkMode(this.checked)"
                                               @endif
                                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ $setting->value == '1' || $setting->value == 'true' ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    @if($setting->key === 'dark_mode')
                                        <p class="mt-1 text-xs text-gray-500">Enable dark mode theme</p>
                                    @endif
                                @elseif($setting->type === 'integer')
                                    <input type="number" 
                                           name="settings[{{ $setting->key }}][value]" 
                                           value="{{ $setting->value }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                @elseif($setting->type === 'json')
                                    <textarea name="settings[{{ $setting->key }}][value]" 
                                              rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ is_string($setting->value) ? $setting->value : json_encode($setting->value, JSON_PRETTY_PRINT) }}</textarea>
                                @else
                                    <input type="text" 
                                           name="settings[{{ $setting->key }}][value]" 
                                           value="{{ $setting->value }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                @endif
                                <input type="hidden" name="settings[{{ $setting->key }}][type]" value="{{ $setting->type }}">
                                <input type="hidden" name="settings[{{ $setting->key }}][key]" value="{{ $setting->key }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($settings->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-900">No Settings Found</p>
                    <p class="text-sm text-gray-500 mt-1">System settings will appear here once they are created.</p>
                </div>
            @else
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors">
                        Save Settings
                    </button>
                </div>
            @endif
        </form>
    </div>

    @push('scripts')
    <script>
        function toggleDarkMode(enabled) {
            if (enabled) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        // Load dark mode preference on page load
        document.addEventListener('DOMContentLoaded', function() {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'enabled') {
                document.documentElement.classList.add('dark');
                const toggle = document.getElementById('darkModeToggle');
                if (toggle) toggle.checked = true;
            }
        });
    </script>
    @endpush
</x-app-layout>
