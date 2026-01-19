<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Record Attendance</h2>
                <p class="text-sm text-gray-600 mt-1">Mark attendance for your students</p>
            </div>
            <a href="{{ route('coach.attendance.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('coach.attendance.store') }}" id="attendance-form">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class Schedule *</label>
                    <select name="class_schedule_id" id="class_schedule_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-base" onchange="this.form.submit()">
                        <option value="">Select a class...</option>
                        @foreach($classSchedules as $schedule)
                            <option value="{{ $schedule->id }}" {{ $selectedScheduleId == $schedule->id ? 'selected' : '' }}>
                                {{ $schedule->class_name ?: 'Schedule' }} - 
                                {{ ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][$schedule->day_of_week] }} 
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Select a class to load students</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Attendance Date *</label>
                    <input type="date" name="attendance_date" value="{{ old('attendance_date', date('Y-m-d')) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-base">
                    <p class="mt-1 text-xs text-gray-500">Default: Today</p>
                </div>
            </div>

            @if($selectedSchedule && $students->count() > 0)
                <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-purple-900">{{ $selectedSchedule->class_name ?: 'Class Schedule' }}</h4>
                            <p class="text-sm text-purple-700">{{ $students->count() }} students in dojo</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="markAll('present')" class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                All Present
                            </button>
                            <button type="button" onclick="markAll('absent')" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                All Absent
                            </button>
                        </div>
                    </div>
                </div>

                <div id="students-container" class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Students ({{ $students->count() }})
                    </h3>
                    <div class="space-y-2">
                        @foreach($students as $student)
                            <div class="flex items-center justify-between p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-purple-300 transition student-row">
                                <div class="flex items-center space-x-4 flex-1">
                                    <input type="hidden" name="attendances[{{ $loop->index }}][member_id]" value="{{ $student->id }}">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <span class="text-purple-600 font-semibold text-sm">{{ substr($student->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900">{{ $student->name }}</p>
                                        @if($student->currentBelt)
                                            <p class="text-xs text-gray-500">{{ $student->currentBelt->name }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <input type="radio" name="attendances[{{ $loop->index }}][status]" value="present" id="present-{{ $loop->index }}" checked class="hidden status-radio" data-row="{{ $loop->index }}">
                                    <label for="present-{{ $loop->index }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-l-lg hover:bg-gray-200 cursor-pointer transition-all font-medium text-sm border-2 border-gray-300 status-label" data-status="present" data-row="{{ $loop->index }}">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Present
                                    </label>
                                    
                                    <input type="radio" name="attendances[{{ $loop->index }}][status]" value="absent" id="absent-{{ $loop->index }}" class="hidden status-radio" data-row="{{ $loop->index }}">
                                    <label for="absent-{{ $loop->index }}" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 cursor-pointer transition-all font-medium text-sm border-y-2 border-gray-300 status-label" data-status="absent" data-row="{{ $loop->index }}">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Absent
                                    </label>
                                    
                                    <input type="radio" name="attendances[{{ $loop->index }}][status]" value="excused" id="excused-{{ $loop->index }}" class="hidden status-radio" data-row="{{ $loop->index }}">
                                    <label for="excused-{{ $loop->index }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-r-lg hover:bg-gray-200 cursor-pointer transition-all font-medium text-sm border-2 border-gray-300 status-label" data-status="excused" data-row="{{ $loop->index }}">
                                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Excused
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="text-sm">
                            <span class="font-semibold text-gray-900" id="present-count">{{ $students->count() }}</span>
                            <span class="text-gray-500">Present</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-semibold text-gray-900" id="absent-count">0</span>
                            <span class="text-gray-500">Absent</span>
                        </div>
                        <div class="text-sm">
                            <span class="font-semibold text-gray-900" id="excused-count">0</span>
                            <span class="text-gray-500">Excused</span>
                        </div>
                    </div>
                    <button type="submit" class="px-8 py-3 bg-purple-600 text-white text-base font-semibold rounded-lg hover:bg-purple-700 transition shadow-lg hover:shadow-xl">
                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Attendance
                    </button>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Select a Class Schedule</h3>
                    <p class="text-gray-500">Choose a class from the dropdown above to view and mark attendance for students.</p>
                </div>
            @endif
        </form>
    </div>

    <script>
        function markAll(status) {
            const radios = document.querySelectorAll(`input[type="radio"][value="${status}"]`);
            radios.forEach(radio => {
                radio.checked = true;
            });
            updateLabels();
            updateCounts();
        }

        function updateLabels() {
            document.querySelectorAll('.status-label').forEach(label => {
                const radio = document.getElementById(label.getAttribute('for'));
                if (radio && radio.checked) {
                    const status = label.dataset.status;
                    if (status === 'present') {
                        label.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
                        label.classList.add('bg-green-600', 'text-white', 'border-green-600');
                    } else if (status === 'absent') {
                        label.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
                        label.classList.add('bg-red-600', 'text-white', 'border-red-600');
                    } else if (status === 'excused') {
                        label.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-300');
                        label.classList.add('bg-yellow-500', 'text-white', 'border-yellow-500');
                    }
                } else {
                    label.classList.remove('bg-green-600', 'bg-red-600', 'bg-yellow-500', 'text-white', 'border-green-600', 'border-red-600', 'border-yellow-500');
                    label.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-300');
                }
            });
        }

        function updateCounts() {
            let presentCount = 0;
            let absentCount = 0;
            let excusedCount = 0;

            const rows = document.querySelectorAll('.student-row');
            rows.forEach(row => {
                const checkedRadio = row.querySelector('input[type="radio"]:checked');
                if (checkedRadio) {
                    if (checkedRadio.value === 'present') presentCount++;
                    else if (checkedRadio.value === 'absent') absentCount++;
                    else if (checkedRadio.value === 'excused') excusedCount++;
                }
            });

            document.getElementById('present-count').textContent = presentCount;
            document.getElementById('absent-count').textContent = absentCount;
            document.getElementById('excused-count').textContent = excusedCount;
        }

        // Update labels and counts when radio buttons change
        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                updateLabels();
                updateCounts();
            });
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateLabels();
            updateCounts();
        });
    </script>
</x-app-layout>

