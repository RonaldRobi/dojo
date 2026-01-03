<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Attendance Details</h2>
                <p class="text-sm text-gray-600 mt-1">View attendance record</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('owner.attendances.edit', $attendance) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('owner.attendances.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Member</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $attendance->member->name ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Class</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $attendance->classSchedule->dojoClass->name ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Attendance Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $attendance->attendance_date->format('M d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @php
                        $statusColors = [
                            'present' => 'bg-green-100 text-green-800',
                            'absent' => 'bg-red-100 text-red-800',
                            'excused' => 'bg-yellow-100 text-yellow-800',
                        ];
                        $color = $statusColors[$attendance->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                        {{ $attendance->status }}
                    </span>
                </dd>
            </div>
            @if($attendance->checked_in_method)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Checked In Method</dt>
                    <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $attendance->checked_in_method }}</dd>
                </div>
            @endif
            @if($attendance->checked_in_at)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Checked In At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $attendance->checked_in_at->format('M d, Y g:i A') }}</dd>
                </div>
            @endif
            @if($attendance->notes)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $attendance->notes }}</dd>
                </div>
            @endif
        </dl>
    </div>
</x-app-layout>

