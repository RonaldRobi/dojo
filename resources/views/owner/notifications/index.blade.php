<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                <p class="text-sm text-gray-600 mt-1">View your notifications</p>
            </div>
            @if($notifications->whereNull('read_at')->count() > 0)
                <form action="{{ route('owner.notifications.read-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex flex-wrap gap-4">
                <select name="read" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Notifications</option>
                    <option value="0" {{ request('read') === '0' ? 'selected' : '' }}>Unread</option>
                    <option value="1" {{ request('read') === '1' ? 'selected' : '' }}>Read</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('owner.notifications.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Clear</a>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-6 hover:bg-gray-50 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                @if(!$notification->read_at)
                                    <span class="h-2 w-2 bg-purple-600 rounded-full mr-3"></span>
                                @endif
                                <h3 class="text-sm font-medium text-gray-900">{{ $notification->title ?? 'Notification' }}</h3>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ $notification->message ?? $notification->content ?? 'No message' }}</p>
                            <p class="mt-2 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!$notification->read_at)
                            <form action="{{ route('owner.notifications.read', $notification) }}" method="POST" class="ml-4">
                                @csrf
                                <button type="submit" class="text-xs text-purple-600 hover:text-purple-800">Mark as read</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p>No notifications found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
