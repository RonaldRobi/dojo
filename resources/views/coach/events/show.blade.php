<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Event Details</p>
            </div>
            <a href="{{ route('coach.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Back
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Event Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 capitalize">
                                {{ $event->type }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Event Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $event->event_date->format('M d, Y g:i A') }}</dd>
                    </div>
                    @if($event->location)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->location }}</dd>
                        </div>
                    @endif
                    @if($event->registration_fee)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Fee</dt>
                            <dd class="mt-1 text-sm text-gray-900">RM {{ number_format($event->registration_fee, 0) }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
            @if($event->description)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Description</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $event->description }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

