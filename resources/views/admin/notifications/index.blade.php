<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
                <p class="text-sm text-gray-500 mt-0.5">View and manage your notifications</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filter Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex gap-2">
                <a href="{{ route('admin.notifications.index', ['read' => 0]) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors {{ !request('read') || request('read') == '0' ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    Unread
                </a>
                <a href="{{ route('admin.notifications.index', ['read' => 1]) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors {{ request('read') == '1' ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    Read
                </a>
                <a href="{{ route('admin.notifications.index') }}" 
                   class="px-4 py-2 rounded-lg font-medium transition-colors {{ !request()->has('read') ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    All
                </a>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="divide-y divide-gray-200">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->link ?? '#' }}" 
                       class="block px-6 py-4 hover:bg-gray-50 transition-colors {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4 mt-1">
                                @if(is_null($notification->read_at))
                                    <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $notification->priority === 'high' ? 'bg-red-100 text-red-800' : ($notification->priority === 'normal' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($notification->priority) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">{{ $notification->message }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                    <span class="text-xs text-gray-400 capitalize">{{ $notification->type }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <p class="text-gray-500">No notifications found</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

