<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Class Schedules
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">View your dojo's weekly class schedule</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($schedules && $schedules->count() > 0)
                <!-- Weekly Schedule Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        @if(isset($schedulesByDay[$day]) && $schedulesByDay[$day]->count() > 0)
                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-xl sm:hover:shadow-2xl">
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 sm:px-6 py-3 sm:py-4">
                                    <h3 class="text-lg sm:text-xl font-bold text-white">{{ $day }}</h3>
                                </div>
                                <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                                    @foreach($schedulesByDay[$day] as $schedule)
                                        <div class="border-l-4 border-indigo-500 bg-indigo-50 p-5 rounded-r-lg hover:shadow-md transition-all duration-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-bold text-gray-900 mb-2">
                                                        {{ $schedule->class_name }}
                                                    </h4>
                                                    <div class="space-y-2">
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <i class="fas fa-clock mr-2 text-indigo-500"></i>
                                                            {{ date('g:i A', strtotime($schedule->start_time)) }} - {{ date('g:i A', strtotime($schedule->end_time)) }}
                                                        </div>
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <i class="fas fa-user mr-2 text-indigo-500"></i>
                                                            {{ $schedule->instructor->name ?? '' }}
                                                        </div>
                                                        @if($schedule->location)
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <i class="fas fa-map-marker-alt mr-2 text-indigo-500"></i>
                                                                {{ $schedule->location }}
                                                            </div>
                                                        @endif
                                                        @if($schedule->class_type)
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <i class="fas fa-tag mr-2 text-indigo-500"></i>
                                                                {{ ucfirst($schedule->class_type) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <a href="{{ route('student.classes.show', $schedule->id) }}" class="ml-4 px-3 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-blue-100 mb-4">
                            <svg class="h-10 w-10 sm:h-12 sm:w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No Schedules Available</h3>
                        <p class="text-sm sm:text-base text-gray-600">There are no class schedules available at your dojo currently.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

