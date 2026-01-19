<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.events.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Create New Event
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Add a new event for dojos</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.events.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf

            <div class="space-y-6">
                <!-- Event Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dojo Selection -->
                <div>
                    <label for="dojo_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Dojo <span class="text-gray-500 text-xs">(Leave empty for all dojos)</span>
                    </label>
                    <select name="dojo_id" id="dojo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('dojo_id') border-red-500 @enderror">
                        <option value="">All Dojos (National Event)</option>
                        @foreach($dojos as $dojo)
                            <option value="{{ $dojo->id }}" data-address="{{ $dojo->address }}" {{ old('dojo_id') == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                        @endforeach
                    </select>
                    @error('dojo_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Event Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('type') border-red-500 @enderror">
                        <option value="">Select Type</option>
                        <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="seminar" {{ old('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="tournament" {{ old('type') == 'tournament' ? 'selected' : '' }}>Tournament</option>
                        <option value="grading" {{ old('type') == 'grading' ? 'selected' : '' }}>Grading</option>
                        <option value="social" {{ old('type') == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Date & Registration Deadline -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Event Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('event_date') border-red-500 @enderror">
                        @error('event_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="registration_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                            Registration Deadline
                        </label>
                        <input type="date" name="registration_deadline" id="registration_deadline" value="{{ old('registration_deadline') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('registration_deadline') border-red-500 @enderror">
                        @error('registration_deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location <span class="text-gray-500 text-xs">(Auto-filled from dojo address)</span>
                    </label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('location') border-red-500 @enderror">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Capacity & Registration Fee -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                            Capacity (Max Participants)
                        </label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}" min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('capacity') border-red-500 @enderror">
                        @error('capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="registration_fee" class="block text-sm font-medium text-gray-700 mb-2">
                            Registration Fee (RM)
                        </label>
                        <input type="number" name="registration_fee" id="registration_fee" value="{{ old('registration_fee', 0) }}" min="0" step="0.01"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('registration_fee') border-red-500 @enderror">
                        @error('registration_fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Is Public & Is Active -->
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Public Event <span class="text-gray-500">(Visible to all users)</span>
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Active Event <span class="text-gray-500">(Enable registration)</span>
                        </span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.events.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                        Create Event
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dojoSelect = document.getElementById('dojo_id');
            const locationInput = document.getElementById('location');
            
            // Auto-fill location when dojo is selected
            dojoSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const dojoAddress = selectedOption.getAttribute('data-address');
                
                // Only fill if there's an address and location is empty
                if (dojoAddress && dojoAddress.trim() !== '') {
                    locationInput.value = dojoAddress;
                } else if (this.value === '') {
                    // Clear location if "All Dojos" is selected
                    locationInput.value = '';
                }
            });
        });
    </script>
    @endpush
</x-app-layout>

