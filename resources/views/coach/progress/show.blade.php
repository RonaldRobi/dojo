<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $member->name }} - Progress</h2>
                <p class="text-sm text-gray-600 mt-1">Track and manage student progress</p>
            </div>
            <a href="{{ route('coach.progress.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Current Belt -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Belt</h3>
                <div class="flex items-center space-x-4">
                    <div class="p-4 bg-purple-100 rounded-lg">
                        <span class="text-2xl font-bold text-purple-800">
                            {{ $member->currentBelt->name ?? 'N/A' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Level: {{ $member->currentBelt->level ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Promote Belt -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Promote Belt</h3>
                <form method="POST" action="{{ route('coach.progress.promote', $member) }}" onsubmit="return confirm('Are you sure you want to promote this student?')">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Select New Rank *</label>
                            <select name="rank_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Select Rank</option>
                                @foreach($ranks as $rank)
                                    @if($member->currentBelt && $rank->level > $member->currentBelt->level)
                                        <option value="{{ $rank->id }}">{{ $rank->name }} (Level {{ $rank->level }})</option>
                                    @elseif(!$member->currentBelt)
                                        <option value="{{ $rank->id }}">{{ $rank->name }} (Level {{ $rank->level }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Promote Student
                        </button>
                    </div>
                </form>
            </div>

            <!-- Progress Logs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Progress Logs</h3>
                @if($progressLogs->count() > 0)
                    <div class="space-y-4">
                        @foreach($progressLogs as $log)
                            <div class="border-l-4 border-purple-500 pl-4 py-2">
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
                @else
                    <p class="text-gray-500">No progress logs yet.</p>
                @endif
            </div>

            <!-- Add Progress Log -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Progress Log</h3>
                <form method="POST" action="{{ route('coach.progress.store', $member) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('notes') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Skills Improved</label>
                            <textarea name="skills_improved" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('skills_improved') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Areas to Improve</label>
                            <textarea name="areas_to_improve" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">{{ old('areas_to_improve') }}</textarea>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Add Progress Log
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Student Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Info</h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900">{{ $member->name }}</dd>
                    </div>
                    @if($member->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="text-sm text-gray-900">{{ $member->phone }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>

