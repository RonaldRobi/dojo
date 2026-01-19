<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $classSchedule->class_name ?: 'Class Schedule' }}</h2>
                <p class="text-sm text-gray-600 mt-1">Class Schedule Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('coach.attendance.create', ['class_schedule_id' => $classSchedule->id]) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    Take Attendance
                </a>
                <a href="{{ route('coach.classes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Schedule Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Schedule Information</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Class Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $classSchedule->class_name ?: 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Day</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$classSchedule->day_of_week] }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Time</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($classSchedule->start_time)->format('g:i A') }} - 
                            {{ \Carbon\Carbon::parse($classSchedule->end_time)->format('g:i A') }}
                        </dd>
                    </div>
                    @if($classSchedule->location)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $classSchedule->location }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $classSchedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $classSchedule->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Students in Dojo -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Students in Dojo ({{ $students->count() }})</h3>
                @if($students->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Belt</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($students as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                {{ $student->currentBelt->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium">
                                            <a href="{{ route('coach.students.show', $student) }}" class="text-purple-600 hover:text-purple-900 mr-3">View</a>
                                            <a href="{{ route('coach.progress.show', $student) }}" class="text-blue-600 hover:text-blue-900">Progress</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No students in dojo yet</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <div class="text-sm text-gray-500">Active Students</div>
                        <div class="text-2xl font-bold text-purple-600">{{ $students->count() }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Total Attendance</div>
                        <div class="text-2xl font-bold text-green-600">{{ $classSchedule->attendances->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('coach.attendance.create', ['class_schedule_id' => $classSchedule->id]) }}" class="block w-full px-4 py-2 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition">
                        Take Attendance
                    </a>
                    <a href="{{ route('coach.students.index', ['class_schedule_id' => $classSchedule->id]) }}" class="block w-full px-4 py-2 bg-blue-600 text-white text-center rounded-lg hover:bg-blue-700 transition">
                        View All Students
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

