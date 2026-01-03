<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $event->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Event Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('owner.events.edit', $event) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('owner.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Event Information</h3>
                <dl class="grid grid-cols-1 gap-4">
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
                    @if($event->capacity)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->registrations->count() }} / {{ $event->capacity }}</dd>
                        </div>
                    @endif
                    @if($event->registration_fee)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Fee</dt>
                            <dd class="mt-1 text-sm text-gray-900">RM {{ number_format($event->registration_fee, 2) }}</dd>
                        </div>
                    @endif
                    @if($event->registration_deadline)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Registration Deadline</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $event->registration_deadline->format('M d, Y') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $event->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($event->is_public)
                                <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Public</span>
                            @endif
                        </dd>
                    </div>
                    @if($event->description)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $event->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            @if($event->registrations->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Registrations ({{ $event->registrations->count() }})</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registered At</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($event->registrations as $registration)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $registration->member->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $registration->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $registration->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($registration->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

