<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Event Management
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Manage all events across all dojos</p>
            </div>
            <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Event
                </span>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('admin.events.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                
                <select name="dojo_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="">All Dojos</option>
                    @foreach($dojos as $dojo)
                        <option value="{{ $dojo->id }}" {{ request('dojo_id') == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                    @endforeach
                </select>

                <select name="type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="">All Types</option>
                    <option value="workshop" {{ request('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="seminar" {{ request('type') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="tournament" {{ request('type') == 'tournament' ? 'selected' : '' }}>Tournament</option>
                    <option value="grading" {{ request('type') == 'grading' ? 'selected' : '' }}>Grading</option>
                    <option value="social" {{ request('type') == 'social' ? 'selected' : '' }}>Social</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>

                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="">All Events</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="past" {{ request('status') == 'past' ? 'selected' : '' }}>Past</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all font-semibold text-sm shadow-md">
                        Filter
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all text-sm font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Events List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($events->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Dojo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fee</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($events as $event)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                        @if($event->is_public)
                                            <span class="text-xs text-green-600">🌐 Public</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $event->dojo->name ?? 'All Dojos' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700">
                                            {{ ucfirst($event->type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        RM {{ number_format($event->registration_fee ?? 0, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(\Carbon\Carbon::parse($event->event_date)->isFuture())
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Upcoming</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Past</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.events.show', $event) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.events.edit', $event) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $events->withQueryString()->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mt-4 text-sm text-gray-500 font-medium">No events found</p>
                    <p class="mt-1 text-xs text-gray-400">Try adjusting your filters or create a new event</p>
                    <a href="{{ route('admin.events.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Create Event
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
    @endif
</x-app-layout>

