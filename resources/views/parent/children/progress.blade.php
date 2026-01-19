<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900">
                Progress & Grades - {{ $member->name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">Monitor your child's progress and grades comprehensively</p>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 space-y-4 sm:space-y-6">
            <div class="mb-6">
                <a href="{{ route('parent.children.show', $member) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
                <!-- Total Attendances -->
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Attendance</p>
                            <p class="mt-2 text-4xl font-bold">{{ $totalAttendances }}</p>
                        </div>
                        <i class="fas fa-check-circle text-white opacity-50" style="font-size: 3rem;"></i>
                    </div>
                </div>

                <!-- Total Classes -->
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active Classes</p>
                            <p class="mt-2 text-4xl font-bold">{{ $totalClasses }}</p>
                        </div>
                        <i class="fas fa-book-open text-white opacity-50" style="font-size: 3rem;"></i>
                    </div>
                </div>

                <!-- Attendance Rate -->
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Attendance Rate</p>
                            <p class="mt-2 text-4xl font-bold">{{ $attendanceRate }}%</p>
                            <p class="mt-1 text-xs text-purple-100">Last 30 days</p>
                        </div>
                        <i class="fas fa-chart-bar text-white opacity-50" style="font-size: 3rem;"></i>
                    </div>
                </div>

                <!-- Current Belt -->
                <div class="bg-gradient-to-br from-yellow-500 to-amber-600 rounded-2xl shadow-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Current Belt</p>
                            <p class="mt-2 text-2xl font-bold">{{ $currentRank->belt->name ?? 'White Belt' }}</p>
                        </div>
                        <i class="fas fa-medal text-white opacity-50" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>

            <!-- Rank History -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Belt Promotion History</h3>
                </x-slot>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Belt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Achieved</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($allRanks as $memberRank)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                            {{ $memberRank->rank->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $memberRank->achieved_at ? $memberRank->achieved_at->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $memberRank->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No belt promotion history yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Grading Results -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Exam / Grading Results</h3>
                </x-slot>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Belt</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Examiner</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($gradingResults as $grading)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $grading->grading_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                            {{ $grading->rank->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($grading->exam_score)
                                            <span class="font-semibold">{{ number_format($grading->exam_score, 0) }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($grading->status)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $grading->status == 'passed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ ucfirst($grading->status) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $grading->instructor->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $grading->notes ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No exam results yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Progress Logs -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Log Progress</h3>
                </x-slot>
                <div class="space-y-4">
                    @forelse($progressLogs as $log)
                        <div class="border-l-4 border-indigo-500 bg-indigo-50 p-5 rounded-r-lg">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">
                                        {{ $log->date->format('d M Y') }}
                                    </h4>
                                    <p class="text-sm text-gray-600">By: {{ $log->instructor->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            @if($log->skills_improved)
                                <div class="mb-3">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-1">Skills Improved:</h5>
                                    <p class="text-sm text-gray-600">{{ $log->skills_improved }}</p>
                                </div>
                            @endif

                            @if($log->areas_to_improve)
                                <div class="mb-3">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-1">Areas to Improve:</h5>
                                    <p class="text-sm text-gray-600">{{ $log->areas_to_improve }}</p>
                                </div>
                            @endif

                            @if($log->notes)
                                <div class="mb-3">
                                    <h5 class="text-sm font-semibold text-gray-700 mb-1">Notes:</h5>
                                    <p class="text-sm text-gray-600">{{ $log->notes }}</p>
                                </div>
                            @endif

                            @if($log->curriculum_items_completed)
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-700 mb-1">Curriculum Items Completed:</h5>
                                    <ul class="list-disc list-inside text-sm text-gray-600">
                                        @foreach($log->curriculum_items_completed as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            No progress logs yet
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

