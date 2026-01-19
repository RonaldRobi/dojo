<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Pricing Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Configure default pricing for fees and services</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
            <h3 class="text-lg font-semibold">Default Pricing</h3>
            <p class="text-sm text-purple-100 mt-1">Set default prices for fees and services</p>
        </div>

        <form method="POST" action="{{ route('admin.pricing.update') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Monthly Fees -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-bold text-blue-900">Monthly Fees</h4>
                            <p class="text-sm text-blue-600 mt-1">Recurring monthly subscription</p>
                        </div>
                        <div class="bg-blue-200 rounded-full p-3">
                            <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-600 font-semibold text-lg">RM</span>
                        <input type="number" name="monthly_fees" value="{{ old('monthly_fees', $pricing['monthly_fees']) }}" step="0.01" min="0" required
                            class="w-full pl-14 pr-4 py-3 text-2xl font-bold border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white @error('monthly_fees') border-red-500 @enderror">
                        @error('monthly_fees')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Annual/Registration Fee -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-bold text-green-900">Annual/Registration Fee</h4>
                            <p class="text-sm text-green-600 mt-1">One-time registration fee</p>
                        </div>
                        <div class="bg-green-200 rounded-full p-3">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-600 font-semibold text-lg">RM</span>
                        <input type="number" name="annual_registration_fee" value="{{ old('annual_registration_fee', $pricing['annual_registration_fee']) }}" step="0.01" min="0" required
                            class="w-full pl-14 pr-4 py-3 text-2xl font-bold border-2 border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white @error('annual_registration_fee') border-red-500 @enderror">
                        @error('annual_registration_fee')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Uniform -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-bold text-purple-900">Uniform</h4>
                            <p class="text-sm text-purple-600 mt-1">Training uniform set</p>
                        </div>
                        <div class="bg-purple-200 rounded-full p-3">
                            <svg class="w-6 h-6 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-600 font-semibold text-lg">RM</span>
                        <input type="number" name="uniform" value="{{ old('uniform', $pricing['uniform']) }}" step="0.01" min="0" required
                            class="w-full pl-14 pr-4 py-3 text-2xl font-bold border-2 border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white @error('uniform') border-red-500 @enderror">
                        @error('uniform')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-blue-800">
                        <p class="font-semibold">About Pricing Settings</p>
                        <ul class="mt-2 space-y-1 list-disc list-inside">
                            <li>These are default prices used across the system</li>
                            <li>Prices can be customized per branch if needed</li>
                            <li>All amounts are in Malaysian Ringgit (RM)</li>
                            <li>Changes will apply to new registrations and invoices</li>
                            <li><strong>Note:</strong> Grading fees are set per event in the Events menu</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Pricing
                    </span>
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

