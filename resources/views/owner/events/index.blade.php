<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Events</h2>
                <p class="text-sm text-gray-600 mt-1">Manage events and tournaments</p>
            </div>
            <a href="{{ route('owner.events.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                Create Event
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex flex-wrap gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                
                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Types</option>
                    <option value="grading" {{ request('type') == 'grading' ? 'selected' : '' }}>Grading</option>
                    <option value="tournament" {{ request('type') == 'tournament' ? 'selected' : '' }}>Tournament</option>
                    <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                </select>

                <select name="is_active" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Status</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('owner.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registrations</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($events as $event)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                <div class="flex items-center gap-2 mt-1">
                                    @if($event->is_public)
                                        <span class="text-xs text-purple-600">Public</span>
                                    @endif
                                    @if(!$event->dojo_id)
                                        <span class="text-xs text-blue-600 font-semibold">All Dojos</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 capitalize">
                                    {{ $event->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $event->event_date->format('M d, Y') }}<br>
                                <span class="text-gray-500">{{ $event->event_date->format('g:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $event->location ?? 'TBA' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $event->registrations->count() }} / {{ $event->capacity ?? '∞' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $event->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $event->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('owner.events.show', $event) }}" class="text-purple-600 hover:text-purple-900 mr-3">View</a>
                                @if($event->dojo_id)
                                    <a href="{{ route('owner.events.edit', $event) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                    <form action="{{ route('owner.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 mr-3" title="Cannot edit events for all dojos">Edit</span>
                                    <span class="text-gray-400" title="Cannot delete events for all dojos">Delete</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No events found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $events->links() }}
        </div>
    </div>
</x-app-layout>
