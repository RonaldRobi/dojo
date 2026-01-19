<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('owner.schedules.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Edit Weekly Schedule
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Update schedule information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('owner.schedules.update', $schedule->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Day of Week -->
                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-2">
                        Day of Week <span class="text-red-500">*</span>
                    </label>
                    <select name="day_of_week" id="day_of_week" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('day_of_week') border-red-500 @enderror">
                        <option value="">Select Day</option>
                        <option value="0" {{ old('day_of_week', $schedule->day_of_week) == '0' ? 'selected' : '' }}>Sunday</option>
                        <option value="1" {{ old('day_of_week', $schedule->day_of_week) == '1' ? 'selected' : '' }}>Monday</option>
                        <option value="2" {{ old('day_of_week', $schedule->day_of_week) == '2' ? 'selected' : '' }}>Tuesday</option>
                        <option value="3" {{ old('day_of_week', $schedule->day_of_week) == '3' ? 'selected' : '' }}>Wednesday</option>
                        <option value="4" {{ old('day_of_week', $schedule->day_of_week) == '4' ? 'selected' : '' }}>Thursday</option>
                        <option value="5" {{ old('day_of_week', $schedule->day_of_week) == '5' ? 'selected' : '' }}>Friday</option>
                        <option value="6" {{ old('day_of_week', $schedule->day_of_week) == '6' ? 'selected' : '' }}>Saturday</option>
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" 
                            value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" 
                            value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Instructor -->
                <div>
                    <label for="instructor_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Instructor <span class="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <select name="instructor_id" id="instructor_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('instructor_id') border-red-500 @enderror">
                        <option value="">No instructor assigned</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('instructor_id', $schedule->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('instructor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }} class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">Active (schedule is currently running)</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('owner.schedules.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                        Update Schedule
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

