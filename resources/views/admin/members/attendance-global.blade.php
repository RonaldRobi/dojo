<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Global Attendance</h2>
            <p class="text-sm text-gray-500 mt-1">Mark attendance for active schedules and view attendance records</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        <!-- Dojo Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" class="flex gap-4">
                <select name="dojo_id" onchange="this.form.submit()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Dojos</option>
                    @foreach($dojos as $dojo)
                        <option value="{{ $dojo->id }}" {{ $selectedDojoId == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Today's Active Schedules -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                <h3 class="text-lg font-semibold">Mark Attendance - {{ $today->format('l, d M Y') }}</h3>
                <p class="text-sm text-purple-100 mt-1">Active schedules for today</p>
            </div>

            @if($activeSchedules->count() > 0)
                <div class="p-6 space-y-6" x-data="{ openSchedule: null }">
                    @foreach($activeSchedules as $schedule)
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <!-- Schedule Header -->
                            <button @click="openSchedule = openSchedule === {{ $schedule->id }} ? null : {{ $schedule->id }}" 
                                class="w-full px-6 py-4 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="bg-purple-100 text-purple-600 rounded-lg p-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-base font-semibold text-gray-900">{{ $schedule->dojo->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            <span class="mx-2">•</span>
                                            <span class="text-purple-600 font-medium">{{ $schedule->instructor->user->name ?? 'No Instructor' }}</span>
                                        </p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 transition-transform" :class="openSchedule === {{ $schedule->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Attendance Form -->
                            <form method="POST" action="{{ route('admin.members.attendance.bulk-store') }}" x-show="openSchedule === {{ $schedule->id }}" x-transition class="p-6 bg-white">
                                @csrf
                                <input type="hidden" name="class_schedule_id" value="{{ $schedule->id }}">
                                <input type="hidden" name="attendance_date" value="{{ $today->format('Y-m-d') }}">

                                @php
                                    $scheduleMembers = $members->where('dojo_id', $schedule->dojo_id);
                                @endphp

                                @if($scheduleMembers->count() > 0)
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Student</th>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Belt</th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Status</th>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Notes</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($scheduleMembers as $index => $member)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                                            {{ $member->name }}
                                                            <input type="hidden" name="attendances[{{ $index }}][member_id]" value="{{ $member->id }}">
                                                        </td>
                                                        <td class="px-4 py-3 text-sm text-gray-600">
                                                            @if($member->currentBelt)
                                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium" style="background-color: {{ $member->currentBelt->color }}20; color: {{ $member->currentBelt->color }};">
                                                                    {{ $member->currentBelt->name }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400">No Belt</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex justify-center gap-2">
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="present" class="w-4 h-4 text-green-600 focus:ring-green-500" required>
                                                                    <span class="ml-2 text-sm text-gray-700">Present</span>
                                                                </label>
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="late" class="w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                                                    <span class="ml-2 text-sm text-gray-700">Late</span>
                                                                </label>
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="absent" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                                                    <span class="ml-2 text-sm text-gray-700">Absent</span>
                                                                </label>
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="radio" name="attendances[{{ $index }}][status]" value="excused" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                                                    <span class="ml-2 text-sm text-gray-700">Excused</span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <input type="text" name="attendances[{{ $index }}][notes]" placeholder="Optional notes" class="w-full px-3 py-1 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                                            Save Attendance
                                        </button>
                                    </div>
                                @else
                                    <div class="text-center py-8 text-gray-500">
                                        <p>No active students in this dojo.</p>
                                    </div>
                                @endif
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mt-4 text-sm text-gray-500 font-medium">No active schedules for today</p>
                    <p class="mt-1 text-xs text-gray-400">Active schedules for {{ $today->format('l') }} will appear here</p>
                </div>
            @endif
        </div>

        <!-- Attendance History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-white">
                <h3 class="text-lg font-semibold text-gray-900">Attendance History</h3>
            </div>
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <input type="hidden" name="dojo_id" value="{{ $selectedDojoId }}">
                    <select name="member_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">All Members</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">Filter</button>
                    <a href="{{ route('admin.members.attendance-global', ['dojo_id' => $selectedDojoId]) }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium text-center">Reset</a>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dojo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Checked In</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $attendance->attendance_date ? \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance->member->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->member->dojo->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $attendance->checked_in_at ? $attendance->checked_in_at->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'present' => 'bg-green-100 text-green-800',
                                            'late' => 'bg-yellow-100 text-yellow-800',
                                            'absent' => 'bg-red-100 text-red-800',
                                            'excused' => 'bg-blue-100 text-blue-800',
                                        ];
                                        $color = $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                        {{ ucfirst($attendance->status ?? 'Unknown') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $attendance->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No attendance records found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">{{ $attendances->links() }}</div>
        </div>
    </div>
</x-app-layout>
