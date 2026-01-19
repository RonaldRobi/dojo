<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('admin.events.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                        Event Details
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1">View event information and registrations</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.events.edit', $event) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg font-semibold hover:bg-yellow-700 transition-all shadow-md">
                    Edit Event
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Event Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white">{{ $event->name }}</h3>
                @if($event->is_public)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-500 rounded-full mt-2">
                        🌐 Public Event
                    </span>
                @endif
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dojo -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Dojo</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">{{ $event->dojo->name ?? 'All Dojos (National Event)' }}</p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Event Type</label>
                        <p class="mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($event->type) }}
                            </span>
                        </p>
                    </div>

                    <!-- Event Date -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Event Date</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->event_date)->format('l, d F Y') }}</p>
                        @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full mt-1">
                                Upcoming
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full mt-1">
                                Past Event
                            </span>
                        @endif
                    </div>

                    <!-- Registration Deadline -->
                    @if($event->registration_deadline)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Registration Deadline</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->registration_deadline)->format('d F Y') }}</p>
                    </div>
                    @endif

                    <!-- Location -->
                    @if($event->location)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Location</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">{{ $event->location }}</p>
                    </div>
                    @endif

                    <!-- Capacity -->
                    @if($event->capacity)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Capacity (Max Participants)</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">{{ $event->capacity }} participants</p>
                    </div>
                    @endif

                    <!-- Registration Fee -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Registration Fee</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">RM {{ number_format($event->registration_fee ?? 0, 0) }}</p>
                    </div>

                    <!-- Registrations Count -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Current Registrations</label>
                        <p class="mt-1 text-base font-semibold text-gray-900">{{ $event->registrations->count() }} registered</p>
                    </div>
                </div>

                <!-- Description -->
                @if($event->description)
                <div class="pt-4 border-t border-gray-200">
                    <label class="text-sm font-medium text-gray-500">Description</label>
                    <p class="mt-2 text-gray-700 whitespace-pre-line">{{ $event->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Registrations List -->
        @if($event->registrations->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-900">Registered Participants ({{ $event->registrations->count() }})</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Participant</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dojo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Registered At</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($event->registrations as $registration)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $registration->member->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $registration->member->member_number ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $registration->member->dojo->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $registration->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($registration->status == 'confirmed')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                @elseif($registration->status == 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($registration->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="mt-4 text-sm text-gray-500 font-medium">No registrations yet</p>
            <p class="mt-1 text-xs text-gray-400">Participants will appear here once they register</p>
        </div>
        @endif
    </div>
</x-app-layout>

