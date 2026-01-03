<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $dojoClass->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Class Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('owner.classes.edit', $dojoClass) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('owner.classes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Class Information</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dojoClass->description ?? 'No description' }}</dd>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dojoClass->capacity }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $dojoClass->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $dojoClass->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                    </div>
                    @if($dojoClass->style)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Style</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $dojoClass->style }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @if($dojoClass->schedules->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Class Schedules</h3>
                    <div class="space-y-3">
                        @foreach($dojoClass->schedules as $schedule)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$schedule->day_of_week] }}</p>
                                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</p>
                                        @if($schedule->instructor)
                                            <p class="text-xs text-gray-500 mt-1">Instructor: {{ $schedule->instructor->name }}</p>
                                        @endif
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Enrollments</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $dojoClass->enrollments->count() }}</p>
                <p class="text-sm text-gray-500 mt-1">Total enrollments</p>
            </div>
        </div>
    </div>
</x-app-layout>

