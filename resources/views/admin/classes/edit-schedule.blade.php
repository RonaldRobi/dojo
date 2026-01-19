<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.classes.calendar') }}" class="mr-4 text-gray-600 hover:text-gray-900">
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
        <form action="{{ route('admin.classes.schedule.update', $schedule->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Current Info Banner -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Current Schedule</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Schedule at {{ $schedule->dojo->name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Branch/Dojo Selection -->
                <div>
                    <label for="dojo_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch/Dojo <span class="text-red-500">*</span>
                    </label>
                    <select name="dojo_id" id="dojo_id" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('dojo_id') border-red-500 @enderror">
                        <option value="">Select Branch</option>
                        @foreach($dojos as $dojo)
                            <option value="{{ $dojo->id }}" {{ old('dojo_id', $schedule->dojo_id) == $dojo->id ? 'selected' : '' }}>
                                {{ $dojo->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('dojo_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


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

                <!-- Is Active -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Active Schedule <span class="text-gray-500">(Visible to students)</span>
                        </span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.classes.calendar') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-blue-700 transition-all shadow-md hover:shadow-lg">
                        Update Schedule
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
