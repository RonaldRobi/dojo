<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Student Dashboard
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Welcome back, {{ $stats['member']->name ?? 'Student' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-8">
            <!-- Member Info Card dengan gradient -->
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
                <div class="p-4 sm:p-6 lg:p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold mb-1 sm:mb-2">{{ $stats['member']->name }}</h3>
                            <p class="text-xs sm:text-sm text-blue-100 mb-2 sm:mb-4">{{ $stats['member']->dojo->name ?? 'N/A' }}</p>
                            @if($stats['current_belt'])
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 sm:px-4 sm:py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-full font-semibold text-xs sm:text-sm">
                                        {{ $stats['current_belt']->belt->name ?? 'No Belt' }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="text-right ml-4">
                            <div class="p-3 sm:p-4 lg:p-6 bg-white bg-opacity-20 backdrop-blur-sm rounded-xl sm:rounded-2xl">
                                <svg class="h-10 w-10 sm:h-12 sm:w-12 lg:h-16 lg:w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
                <!-- Enrolled Classes -->
                <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl sm:rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-4 sm:p-5 lg:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-xs sm:text-sm font-medium uppercase tracking-wide">Enrolled Classes</p>
                                <p class="mt-1 sm:mt-2 text-3xl sm:text-4xl font-bold text-white">{{ $stats['total_classes'] }}</p>
                            </div>
                            <div class="p-3 sm:p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Attendance -->
                <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl sm:rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-4 sm:p-5 lg:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-xs sm:text-sm font-medium uppercase tracking-wide">This Month</p>
                                <p class="mt-1 sm:mt-2 text-3xl sm:text-4xl font-bold text-white">{{ $stats['monthly_attendance'] }}</p>
                                <p class="mt-1 text-[10px] sm:text-xs text-green-100">Classes attended</p>
                            </div>
                            <div class="p-3 sm:p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Belt -->
                @php
                    $beltColor = $stats['current_belt']->belt->color ?? '#EAB308'; // Default yellow
                    // Calculate brightness for text color
                    $r = hexdec(substr($beltColor, 1, 2));
                    $g = hexdec(substr($beltColor, 3, 2));
                    $b = hexdec(substr($beltColor, 5, 2));
                    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
                    $beltTextColor = $brightness > 155 ? 'text-gray-900' : 'text-white';
                    $beltSubtextColor = $brightness > 155 ? 'text-gray-700' : 'text-gray-100';
                @endphp
                <div class="group relative rounded-xl sm:rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden" style="background-color: {{ $beltColor }};">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-4 sm:p-5 lg:p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="{{ $beltSubtextColor }} text-xs sm:text-sm font-medium uppercase tracking-wide">Current Belt</p>
                                <p class="mt-1 sm:mt-2 text-xl sm:text-2xl font-bold {{ $beltTextColor }}">{{ $stats['current_belt']->belt->name ?? 'White Belt' }}</p>
                                <p class="mt-1 text-[10px] sm:text-xs {{ $beltSubtextColor }}">Your current rank</p>
                            </div>
                            <div class="p-3 sm:p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-8 w-8 sm:h-10 sm:w-10 {{ $beltTextColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Classes -->
            @if($stats['enrolled_classes']->count() > 0)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 sm:px-6 py-4 sm:py-5">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-white">My Classes</h3>
                                <p class="text-xs sm:text-sm text-blue-100">Classes you're enrolled in</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            @foreach($stats['enrolled_classes'] as $enrollment)
                                <div class="p-4 sm:p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border-l-4 border-blue-500 hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                    <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2">{{ $enrollment->classSchedule->dojoClass->name ?? 'N/A' }}</h4>
                                    <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3">{{ $enrollment->classSchedule->dojoClass->description ?? 'No description' }}</p>
                                    <div class="flex items-center space-x-3 sm:space-x-4 text-[10px] sm:text-xs text-gray-500">
                                        <span class="flex items-center">
                                            <svg class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($enrollment->classSchedule->start_time)->format('g:i A') }}
                                        </span>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full font-semibold">
                                            Active
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Upcoming Events -->
            @if($stats['upcoming_events']->count() > 0)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-700 px-4 sm:px-6 py-4 sm:py-5">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-white">Upcoming Events</h3>
                                <p class="text-xs sm:text-sm text-purple-100">Events you're registered for</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($stats['upcoming_events'] as $eventReg)
                                <div class="flex items-center justify-between p-4 sm:p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border-l-4 border-purple-500 hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                    <div class="flex-1 pr-2">
                                        <h4 class="text-base sm:text-lg font-bold text-gray-900">{{ $eventReg->event->name ?? 'N/A' }}</h4>
                                        <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $eventReg->event->description ?? 'No description' }}</p>
                                        <p class="text-[10px] sm:text-xs text-purple-600 font-medium mt-2">
                                            {{ $eventReg->event->event_date->format('F d, Y • g:i A') }}
                                        </p>
                                    </div>
                                    <div class="p-3 sm:p-4 bg-purple-500 rounded-xl flex-shrink-0">
                                        <svg class="h-6 w-6 sm:h-8 sm:w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

