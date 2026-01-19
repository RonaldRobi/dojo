<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">My Classes</h2>
                <p class="text-sm text-gray-600 mt-1">Manage your teaching schedule</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Class Schedules -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($schedules as $schedule)
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-lg shadow-sm border border-purple-200 hover:shadow-md transition overflow-hidden">
                        <div class="p-5">
                            <div class="mb-3">
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $schedule->class_name ?: 'Class Schedule' }}</h3>
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-600 text-white">
                                        {{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$schedule->day_of_week] }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="space-y-2 text-sm text-gray-700 mb-4">
                                @if($schedule->class_type)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ ucfirst($schedule->class_type) }}
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}
                                </div>
                                @if($schedule->location)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        {{ $schedule->location }}
                                    </div>
                                @endif
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Weekly Schedule
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('coach.classes.show', $schedule) }}" class="text-purple-600 hover:text-purple-900 font-medium text-sm">
                                    View Details →
                                </a>
                                <a href="{{ route('coach.attendance.create', ['class_schedule_id' => $schedule->id]) }}" class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition">
                                    Take Attendance
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No schedules assigned</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't been assigned to any class schedules yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

