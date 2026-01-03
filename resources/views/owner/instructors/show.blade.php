<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $instructor->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Instructor Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('owner.instructors.edit', $instructor) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('owner.instructors.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructor Information</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $instructor->name }}</dd>
                    </div>
                    @if($instructor->email)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructor->email }}</dd>
                        </div>
                    @endif
                    @if($instructor->phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructor->phone }}</dd>
                        </div>
                    @endif
                    @if($instructor->specialization)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Specialization</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructor->specialization }}</dd>
                        </div>
                    @endif
                    @if($instructor->certification_level)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Certification Level</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructor->certification_level }}</dd>
                        </div>
                    @endif
                    @if($instructor->hire_date)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Hire Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $instructor->hire_date->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $instructor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($instructor->status) }}
                            </span>
                        </dd>
                    </div>
                    @if($instructor->bio)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bio</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $instructor->bio }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @if($instructor->schedules->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Class Schedules</h3>
                    <div class="space-y-3">
                        @foreach($instructor->schedules as $schedule)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $schedule->dojoClass->name ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$schedule->day_of_week] }}</p>
                                        <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</p>
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
    </div>
</x-app-layout>

