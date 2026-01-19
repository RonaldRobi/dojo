<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Coach Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">Welcome back, {{ $instructor->name ?? ($stats['instructor']->name ?? 'Coach') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Today's Classes -->
                <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Today's Classes</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['today_classes']->count() }}</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Students -->
                <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium uppercase tracking-wide">Total Students</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['total_students'] }}</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- This Week Attendance -->
                <div class="group relative bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-purple-100 text-sm font-medium uppercase tracking-wide">Week Attendance</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['this_week_attendance'] }}</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Classes Detail -->
            @if($stats['today_classes']->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Today's Schedule</h3>
                                <p class="text-sm text-blue-100">{{ now()->format('l, F d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($stats['today_classes'] as $schedule)
                                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border-l-4 border-blue-500 hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-4 bg-blue-500 rounded-xl shadow-lg">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">{{ $schedule->class_name ?: 'Class Schedule' }}</h4>
                                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</p>
                                            <p class="text-xs text-blue-600 font-medium mt-1">{{ $schedule->enrollments->count() }} students enrolled</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                            Active
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Upcoming Classes -->
            @if($stats['upcoming_classes']->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-5">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Upcoming Classes</h3>
                                <p class="text-sm text-purple-100">Your next scheduled classes</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($stats['upcoming_classes'] as $schedule)
                                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border-l-4 border-purple-500 hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                    <div class="flex items-center space-x-4">
                                        <div class="p-4 bg-purple-500 rounded-xl shadow-lg">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">{{ $schedule->class_name ?: 'Class Schedule' }}</h4>
                                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</p>
                                            <p class="text-xs text-purple-600 font-medium mt-1">
                                                {{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$schedule->day_of_week] }}
                                            </p>
                                        </div>
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

