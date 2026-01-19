@auth
<!-- Mobile Drawer - Always show for authenticated users -->
<div class="lg:hidden">
    <!-- Drawer Overlay -->
    <div x-show="drawerOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="drawerOpen = false"
         class="fixed inset-0 bg-gray-600 bg-opacity-75 z-[55]" x-cloak></div>

    <!-- Drawer -->
    <div x-show="drawerOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-[60] w-64 bg-sidebar shadow-xl overflow-y-auto"
         @click.away="drawerOpen = false" x-cloak>
        
        <!-- Drawer Header -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-sidebar-700">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-xl flex items-center justify-center overflow-hidden bg-white">
                    <img src="{{ asset('storage/logo.png') }}" alt="Droplets Dojo Logo" class="h-full w-full object-contain p-1">
                </div>
                <div>
                    <h1 class="text-base font-bold text-white">Droplets Dojo</h1>
                    <p class="text-xs text-gray-400">Menu</p>
                </div>
            </div>
            <button @click="drawerOpen = false" class="text-gray-400 hover:text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Drawer Content -->
        @php
            // Check if user is a student - either by role OR by having a Member record
            $isStudent = hasRole('student', currentDojo()) || 
                         hasRole('student') || 
                         \App\Models\Member::where('user_id', auth()->id())
                                           ->where('dojo_id', currentDojo())
                                           ->exists();
        @endphp
        <nav class="px-3 py-4 space-y-1">
            @if($isStudent)
                <!-- Student Menu -->
                <!-- Home -->
                <a href="{{ route('student.dashboard') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Home</span>
                </a>

                <!-- My Classes -->
                <a href="{{ route('student.classes.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.classes.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>My Classes</span>
                </a>

                <!-- My Belt Progress -->
                <a href="{{ route('student.progress.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.progress.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                    <span>My Belt Progress</span>
                </a>

                <!-- News & Updates -->
                <a href="{{ route('student.announcements.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.announcements.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span>News & Updates</span>
                </a>

                <!-- Payments -->
                <a href="{{ route('student.payments.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.payments.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Payments</span>
                </a>
            @else
                <!-- Parent Menu -->
                <div x-data="{ 
                    openMenus: {
                        parentChildren: {{ request()->routeIs('parent.children.*') ? 'true' : 'false' }},
                        parentEvents: {{ request()->routeIs('parent.events.*') ? 'true' : 'false' }},
                        parentPayments: {{ request()->routeIs('parent.payments.*') ? 'true' : 'false' }}
                    }
                }">
                    <!-- Dashboard -->
                    <a href="{{ route('parent.dashboard') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.dashboard') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- My Children -->
                    <div>
                        <button @click="openMenus.parentChildren = !openMenus.parentChildren" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.children.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>My Children</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform" :class="openMenus.parentChildren ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.parentChildren" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('parent.children.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.children.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Children List
                            </a>
                            <a href="{{ route('parent.register.create') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.register.create') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Register Child
                            </a>
                        </div>
                    </div>

                    <!-- Schedules -->
                    <a href="{{ route('parent.schedules.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.schedules.*') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Class Schedules</span>
                    </a>

                    <!-- Events -->
                    <div>
                        <button @click="openMenus.parentEvents = !openMenus.parentEvents" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.events.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Events</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform" :class="openMenus.parentEvents ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.parentEvents" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('parent.events.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.events.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                All Events
                            </a>
                        </div>
                    </div>

                    <!-- Payments -->
                    <div>
                        <button @click="openMenus.parentPayments = !openMenus.parentPayments" class="flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.payments.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span>Payments</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform" :class="openMenus.parentPayments ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.parentPayments" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('parent.payments.index') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.payments.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Payment History
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profile Section - Always at bottom -->
            <div class="mt-6 pt-4 border-t border-sidebar-700">
                <div class="px-3 py-2">
                    <div class="flex items-center space-x-3 px-3 py-3 bg-sidebar-600 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">
                                @if($isStudent)
                                    Student
                                @else
                                    Parent
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Profile Menu Items -->
                <div class="space-y-1 px-3">
                    <a href="{{ route('profile.edit') }}" @click="drawerOpen = false" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-gray-300 hover:bg-sidebar-600 hover:text-white">
                        <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Edit Profile</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full text-left px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-red-400 hover:bg-red-900 hover:bg-opacity-20 hover:text-red-300">
                            <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</div>
@endauth
