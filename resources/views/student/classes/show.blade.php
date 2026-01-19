<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Class Schedule Details
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $schedule->class_name ?? 'N/A' }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Back Button (Top) -->
            <div class="flex justify-start">
                <a href="{{ route('student.classes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Schedules
                </a>
            </div>

            <!-- Class Schedule Information Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 sm:px-6 py-4 sm:py-5">
                    <h3 class="text-lg sm:text-xl font-bold text-white">Schedule Information</h3>
                </div>
                <div class="p-4 sm:p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Class Name</label>
                            <p class="text-base font-bold text-gray-900">{{ $schedule->class_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Class Type</label>
                            <p class="text-base font-bold text-gray-900">{{ ucfirst($schedule->class_type ?? 'N/A') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Day of Week</label>
                            <p class="text-base font-bold text-gray-900">
                                @php
                                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                @endphp
                                {{ $days[$schedule->day_of_week] ?? '' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Time</label>
                            <p class="text-base font-bold text-gray-900">
                                {{ date('g:i A', strtotime($schedule->start_time)) }} - 
                                {{ date('g:i A', strtotime($schedule->end_time)) }}
                            </p>
                        </div>
                        @if($schedule->instructor)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Instructor</label>
                                <p class="text-base font-bold text-gray-900">{{ $schedule->instructor->name ?? 'N/A' }}</p>
                            </div>
                        @endif
                        @if($schedule->location)
                            <div>
                                <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Location</label>
                                <p class="text-base font-bold text-gray-900">{{ $schedule->location }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Status</label>
                            @if($schedule->is_active)
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Attendance Card -->
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-4 sm:px-6 py-4 sm:py-5">
                    <h3 class="text-lg sm:text-xl font-bold text-white">My Attendance History</h3>
                </div>
                <div class="p-4 sm:p-6">
                    @if($attendances && $attendances->count() > 0)
                        <div class="space-y-3">
                            @foreach($attendances as $attendance)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($attendance->status === 'present')
                                                <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-check text-green-600"></i>
                                                </div>
                                            @elseif($attendance->status === 'absent')
                                                <div class="h-10 w-10 bg-red-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-times text-red-600"></i>
                                                </div>
                                            @else
                                                <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-exclamation text-yellow-600"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">
                                                {{ $attendance->attendance_date->format('F d, Y') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ ucfirst($attendance->status) }}
                                                @if($attendance->notes)
                                                    - {{ $attendance->notes }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $attendances->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                <i class="fas fa-clipboard-list text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-600">No attendance records yet for this class</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

