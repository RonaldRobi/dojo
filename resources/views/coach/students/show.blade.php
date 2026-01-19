<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $member->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Student Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('coach.progress.show', $member) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    View Progress
                </a>
                <a href="{{ route('coach.students.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->phone ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->user->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->birth_date ? $member->birth_date->format('M d, Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($member->gender ?? 'N/A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : ($member->status === 'leave' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Join Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->join_date ? $member->join_date->format('M d, Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Style</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->style ?? 'N/A' }}</dd>
                    </div>
                    @if($member->address)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Address</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->address }}</dd>
                        </div>
                    @endif
                    @if($member->medical_notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Medical Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 text-red-600">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                {{ $member->medical_notes }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Current Rank -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Rank</h3>
                @if($member->currentBelt)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full flex items-center justify-center text-white font-bold text-xl" style="background-color: {{ $member->currentBelt->color ?? '#6B7280' }}">
                                {{ substr($member->currentBelt->name, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $member->currentBelt->name }}</h4>
                            <p class="text-sm text-gray-500">Level {{ $member->currentBelt->level }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No rank assigned yet</p>
                @endif
            </div>

            <!-- Recent Attendance -->
            @if($member->attendances->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Attendance (Last 20)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($member->attendances as $attendance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $attendance->attendance_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $attendance->classSchedule->class_name ?: 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : ($attendance->status === 'excused' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Attendance</h3>
                    <p class="text-gray-500 text-center py-4">No attendance records yet</p>
                </div>
            @endif

            <!-- Progress Logs -->
            @if($member->progressLogs->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Progress Notes</h3>
                    <div class="space-y-4">
                        @foreach($member->progressLogs as $log)
                            <div class="border-l-4 border-purple-500 pl-4 py-2 bg-purple-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $log->date->format('M d, Y') }}</p>
                                        @if($log->notes)
                                            <p class="text-sm text-gray-600 mt-1">{{ $log->notes }}</p>
                                        @endif
                                        @if($log->skills_improved)
                                            <p class="text-sm text-green-600 mt-1"><strong>Improved:</strong> {{ $log->skills_improved }}</p>
                                        @endif
                                        @if($log->areas_to_improve)
                                            <p class="text-sm text-yellow-600 mt-1"><strong>To Improve:</strong> {{ $log->areas_to_improve }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('coach.progress.show', $member) }}" class="block w-full px-4 py-2 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition">
                        View Full Progress
                    </a>
                    <a href="{{ route('coach.progress.show', $member) }}" class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                        Promote Belt
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">Total Attendance</div>
                        <div class="text-2xl font-bold text-purple-600">{{ $member->attendances->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Present</div>
                        <div class="text-2xl font-bold text-green-600">{{ $member->attendances->where('status', 'present')->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Total Attendance</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $member->attendances->count() }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>


