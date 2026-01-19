<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Weekly Schedules</h2>
                <p class="text-sm text-gray-500 mt-1">Manage your branch weekly schedules</p>
            </div>
            <a href="{{ route('owner.schedules.create') }}" class="px-4 py-2 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-blue-700 transition-all shadow-md hover:shadow-lg">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Schedule
                </span>
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Weekly Schedule Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase w-32">Day</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Instructor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $dojoId = currentDojo();
                        $allSchedules = \App\Models\ClassSchedule::with(['dojo', 'instructor.user'])
                            ->where('dojo_id', $dojoId)
                            ->orderBy('day_of_week')
                            ->orderBy('start_time')
                            ->get()
                            ->groupBy('day_of_week');
                    @endphp

                    @forelse($allSchedules as $dayOfWeek => $daySchedules)
                        @foreach($daySchedules as $index => $schedule)
                            <tr class="hover:bg-gray-50 {{ $index > 0 ? 'border-t-0' : '' }}">
                                @if($index === 0)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 bg-gray-50 border-r border-gray-200" rowspan="{{ $daySchedules->count() }}">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center mr-3">
                                                <span class="text-purple-600 font-bold text-xs">{{ substr($days[$dayOfWeek], 0, 3) }}</span>
                                            </div>
                                            <span>{{ $days[$dayOfWeek] }}</span>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-' }} - 
                                    {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $schedule->instructor ? $schedule->instructor->user->name : 'No instructor' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('owner.schedules.edit', $schedule->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('owner.schedules.destroy', $schedule->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-gray-500 font-medium">No schedules found</p>
                                <p class="mt-1 text-xs text-gray-400">Create your first schedule to get started</p>
                                <a href="{{ route('owner.schedules.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Create Schedule
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

