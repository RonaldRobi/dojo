<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Events</h2>
                <p class="text-sm text-gray-600 mt-1">View upcoming events</p>
            </div>
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

                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('coach.events.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </form>
        </div>

        <!-- Events Grid -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 capitalize">
                                    {{ $event->type }}
                                </span>
                                @if(!$event->dojo_id)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        All Dojos
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $event->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $event->description }}</p>
                            <div class="space-y-2 text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $event->event_date->format('M d, Y g:i A') }}
                                </div>
                                @if($event->location)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->location }}
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('coach.events.show', $event) }}" class="text-purple-600 hover:text-purple-900 font-medium text-sm">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">No events found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $events->links() }}
        </div>
    </div>
</x-app-layout>

