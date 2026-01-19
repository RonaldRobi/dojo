@auth
<div class="bg-white border-b border-gray-200">
    <div class="px-3 sm:px-4 lg:px-6 py-3 lg:py-4">
        <div class="flex items-center justify-between">
            <!-- Page Title -->
            <div class="flex-1 min-w-0">
                @if(isset($header))
                    {{ $header }}
                @else
                    <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 truncate">
                        @if(request()->routeIs('admin.*'))
                            Admin Dashboard
                        @elseif(request()->routeIs('owner.*'))
                            Owner Dashboard
                        @elseif(request()->routeIs('coach.*'))
                            Coach Dashboard
                        @elseif(request()->routeIs('student.*'))
                            Student Dashboard
                        @elseif(request()->routeIs('parent.*'))
                            Parent Dashboard
                        @else
                            Dashboard
                        @endif
                    </h2>
                @endif
            </div>

            <!-- Right Actions -->
            <div class="flex items-center space-x-2 sm:space-x-4 ml-2">
                <!-- Search Menu -->
                <div class="relative hidden md:block" x-data="{ 
                    open: false, 
                    query: '', 
                    results: [], 
                    loading: false
                }" @click.away="open = false">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        x-model="query"
                        @input.debounce.300ms="
                            if (query.length >= 2) {
                                loading = true;
                                fetch('{{ route('menu-search') }}?q=' + encodeURIComponent(query))
                                    .then(response => response.json())
                                    .then(data => {
                                        results = data.results;
                                        loading = false;
                                        open = true;
                                    })
                                    .catch(() => {
                                        loading = false;
                                        results = [];
                                    });
                            } else {
                                results = [];
                                open = false;
                            }
                        "
                        @focus="if (results.length > 0) open = true"
                        placeholder="Search menu..." 
                        class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    
                    <!-- Loading Indicator -->
                    <div x-show="loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                        <svg class="animate-spin h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    
                    <!-- Search Results Dropdown -->
                    <div x-show="open && results.length > 0" 
                         x-transition
                         class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 max-h-96 overflow-y-auto">
                        <div class="py-2">
                            <template x-for="(result, index) in results" :key="index">
                                <a :href="result.url" class="flex items-center px-4 py-3 hover:bg-gray-100 border-b border-gray-100 last:border-0">
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="result.name"></p>
                                        <p class="text-xs text-gray-500 truncate" x-text="result.category"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                @if(auth()->user()->hasRole('super_admin'))
                <div class="relative" x-data="notificationBell()" x-init="init()">
                    <button 
                        @click="open = !open" 
                        type="button" 
                        class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-lg">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" class="absolute top-0 right-0 flex items-center justify-center min-w-[20px] h-5 px-1.5 text-xs font-bold text-white bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- Notification Dropdown -->
                    <div 
                        x-show="open" 
                        @click.away="open = false" 
                        x-transition
                        class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50 max-h-[500px] overflow-hidden flex flex-col">
                        <!-- Header -->
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            <div class="flex items-center gap-2">
                                <button 
                                    @click="markAllAsRead()"
                                    x-show="unreadCount > 0"
                                    class="text-xs text-purple-600 hover:text-purple-700 font-medium">
                                    Mark all as read
                                </button>
                                <a href="{{ route('admin.notifications.index') }}" class="text-xs text-gray-500 hover:text-gray-700">
                                    View all
                                </a>
                            </div>
                        </div>

                        <!-- Notifications List -->
                        <div class="overflow-y-auto max-h-[400px]">
                            <template x-if="loading">
                                <div class="p-4 text-center text-gray-500">
                                    <svg class="animate-spin h-5 w-5 mx-auto text-purple-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </template>
                            
                            <template x-if="!loading && notifications.length === 0">
                                <div class="p-8 text-center text-gray-500">
                                    <svg class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <p class="text-sm">No notifications</p>
                                </div>
                            </template>

                            <template x-if="!loading && notifications.length > 0">
                                <div>
                                    <template x-for="(notification, index) in notifications" :key="notification.id">
                                        <a 
                                            :href="notification.link || '#'"
                                            @click="markAsRead(notification.id, $event)"
                                            class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors"
                                            :class="notification.is_read ? 'bg-white' : 'bg-blue-50'">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mr-3 mt-0.5">
                                                    <div class="w-2 h-2 rounded-full" :class="notification.is_read ? 'bg-transparent' : 'bg-purple-500'"></div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate" x-text="notification.title"></p>
                                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2" x-text="notification.message"></p>
                                                    <p class="text-xs text-gray-400 mt-1" x-text="notification.created_at"></p>
                                                </div>
                                                <div class="flex-shrink-0 ml-2">
                                                    <span 
                                                        class="px-2 py-0.5 text-xs font-medium rounded-full"
                                                        :class="{
                                                            'bg-red-100 text-red-800': notification.priority === 'high',
                                                            'bg-yellow-100 text-yellow-800': notification.priority === 'normal',
                                                            'bg-gray-100 text-gray-800': notification.priority === 'low'
                                                        }"
                                                        x-text="notification.priority"></span>
                                                </div>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                @push('scripts')
                <script>
                    function notificationBell() {
                        return {
                            open: false,
                            notifications: [],
                            unreadCount: 0,
                            loading: true,
                            intervalId: null,

                            init() {
                                this.fetchNotifications();
                                // Poll every 30 seconds
                                this.intervalId = setInterval(() => {
                                    this.fetchNotifications();
                                }, 30000);
                            },

                            async fetchNotifications() {
                                try {
                                    const response = await fetch('{{ route('admin.notifications.api') }}');
                                    const data = await response.json();
                                    this.notifications = data.notifications || [];
                                    this.unreadCount = data.unread_count || 0;
                                    this.loading = false;
                                } catch (error) {
                                    console.error('Error fetching notifications:', error);
                                    this.loading = false;
                                }
                            },

                            async markAsRead(notificationId, event) {
                                event.preventDefault();
                                const link = this.notifications.find(n => n.id === notificationId)?.link;
                                
                                try {
                                    const response = await fetch(`/admin/notifications/${notificationId}/read`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                                        },
                                    });
                                    
                                    if (response.ok) {
                                        // Update notification in list
                                        const index = this.notifications.findIndex(n => n.id === notificationId);
                                        if (index !== -1) {
                                            this.notifications[index].is_read = true;
                                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                                        }
                                        
                                        // Navigate to link if exists
                                        if (link) {
                                            window.location.href = link;
                                        }
                                    }
                                } catch (error) {
                                    console.error('Error marking notification as read:', error);
                                    // Still navigate to link on error
                                    if (link) {
                                        window.location.href = link;
                                    }
                                }
                            },

                            async markAllAsRead() {
                                try {
                                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                                    const response = await fetch('{{ route('admin.notifications.read-all') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,
                                            'X-Requested-With': 'XMLHttpRequest'
                                        },
                                    });
                                    
                                    if (response.ok) {
                                        this.notifications.forEach(n => n.is_read = true);
                                        this.unreadCount = 0;
                                    }
                                } catch (error) {
                                    console.error('Error marking all as read:', error);
                                }
                            }
                        }
                    }
                </script>
                @endpush
                @else
                <button type="button" class="relative p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-lg">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </button>
                @endif

                <!-- Mobile Drawer Button (Only on mobile, next to notifications) -->
                <button @click="drawerOpen = !drawerOpen" 
                        class="lg:hidden p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- User Menu (Desktop Only) -->
                <div class="relative hidden lg:block" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors">
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white font-semibold shadow-md">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">
                                @php
                                    $roles = auth()->user()->roles()->pluck('name')->map(fn($r) => ucfirst($r))->join(', ');
                                @endphp
                                {{ $roles }}
                            </p>
                        </div>
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Your Profile
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Edit Profile
                            </a>
                            @if(auth()->user()->hasRole('super_admin'))
                            <a href="{{ route('admin.system.settings') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                System Settings
                            </a>
                            @endif
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="h-4 w-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endauth
