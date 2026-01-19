<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Attendance</h2>
                <p class="text-sm text-gray-600 mt-1">Manage student attendance</p>
            </div>
            <a href="{{ route('coach.attendance.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                Record Attendance
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex flex-wrap gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                
                <select name="class_schedule_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Classes</option>
                    @foreach($classSchedules as $schedule)
                        <option value="{{ $schedule->id }}" {{ request('class_schedule_id') == $schedule->id ? 'selected' : '' }}>
                            {{ $schedule->class_name ?: 'N/A' }} - {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">

                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('coach.attendance.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->attendance_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attendance->member->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $attendance->classSchedule->class_name ?: 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : ($attendance->status === 'absent' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $attendance->checked_in_method ? ucfirst($attendance->checked_in_method) : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No attendance records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $attendances->links() }}
        </div>
    </div>
</x-app-layout>

