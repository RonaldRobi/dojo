<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    My Progress
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Track your martial arts journey</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            <!-- Current Belt Card -->
            @php
                $currentBeltColor = $member->currentBelt->color ?? '#EAB308'; // Default yellow
                // Determine text color based on background brightness
                $r = hexdec(substr($currentBeltColor, 1, 2));
                $g = hexdec(substr($currentBeltColor, 3, 2));
                $b = hexdec(substr($currentBeltColor, 5, 2));
                $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
                $textColor = $brightness > 155 ? 'text-gray-900' : 'text-white';
                $subtextColor = $brightness > 155 ? 'text-gray-700' : 'text-gray-100';
            @endphp
            <div class="rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden" style="background-color: {{ $currentBeltColor }};">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <div class="{{ $textColor }}">
                            <p class="text-sm sm:text-base font-medium uppercase tracking-wide mb-2 {{ $subtextColor }}">Current Belt</p>
                            <h3 class="text-3xl sm:text-4xl font-bold">{{ $member->currentBelt->name ?? 'White Belt' }}</h3>
                            @if($nextRank)
                                <p class="text-sm mt-3 {{ $subtextColor }}">Next: {{ $nextRank->name }}</p>
                            @endif
                        </div>
                        <div class="p-4 sm:p-6 bg-white bg-opacity-20 rounded-2xl backdrop-blur-sm">
                            <svg class="h-12 w-12 sm:h-16 sm:w-16 {{ $textColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rank History -->
            @if($rankHistory && $rankHistory->count() > 0)
                <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 sm:px-6 py-4 sm:py-5">
                        <h3 class="text-lg sm:text-xl font-bold text-white">Rank History</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="space-y-4">
                            @foreach($rankHistory as $rank)
                                @php
                                    $rankColor = $rank->rank->color ?? '#3B82F6'; // Default blue
                                    // Calculate brightness for text color
                                    $r = hexdec(substr($rankColor, 1, 2));
                                    $g = hexdec(substr($rankColor, 3, 2));
                                    $b = hexdec(substr($rankColor, 5, 2));
                                    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
                                    $iconTextColor = $brightness > 155 ? 'text-gray-900' : 'text-white';
                                @endphp
                                <div class="flex items-center p-4 rounded-xl border-l-4" style="background-color: {{ $rankColor }}20; border-color: {{ $rankColor }};">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-lg flex items-center justify-center" style="background-color: {{ $rankColor }};">
                                        <svg class="h-6 w-6 {{ $iconTextColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-base sm:text-lg font-bold text-gray-900">{{ $rank->rank->name ?? 'N/A' }}</h4>
                                        <p class="text-xs sm:text-sm text-gray-600">
                                            Achieved on {{ $rank->achieved_at ? \Carbon\Carbon::parse($rank->achieved_at)->format('F d, Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Progress Logs -->
            @if($progressLogs && $progressLogs->count() > 0)
                <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-700 px-4 sm:px-6 py-4 sm:py-5">
                        <h3 class="text-lg sm:text-xl font-bold text-white">Recent Progress Notes</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="space-y-4">
                            @foreach($progressLogs as $log)
                                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border-l-4 border-purple-500">
                                    <div class="flex items-start justify-between mb-2">
                                        <p class="text-xs sm:text-sm font-medium text-purple-600">
                                            {{ $log->created_at->format('F d, Y • g:i A') }}
                                        </p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ ucfirst($log->type ?? 'note') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700 mb-2">{{ $log->notes ?? 'No notes' }}</p>
                                    @if($log->instructor)
                                        <p class="text-xs text-gray-500">
                                            Instructor: {{ $log->instructor->name }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-purple-100 mb-4">
                            <svg class="h-10 w-10 sm:h-12 sm:w-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No Progress Notes Yet</h3>
                        <p class="text-sm sm:text-base text-gray-600">Your progress notes will appear here as your instructors add them.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

