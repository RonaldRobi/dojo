@auth
<aside class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex flex-col flex-grow bg-sidebar overflow-y-auto">
            <!-- Logo Section -->
            <div class="flex items-center flex-shrink-0 px-6 py-5 border-b border-sidebar-700">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-gradient-to-br from-purple-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Droplets Dojo</h1>
                        <p class="text-xs text-gray-400">Management System</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 space-y-1" x-data="{ 
                openMenus: {
                    organization: {{ request()->routeIs('admin.dojos.*') ? 'true' : 'false' }},
                    users: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }},
                    curriculum: {{ request()->routeIs('admin.curriculum.*') ? 'true' : 'false' }},
                    classes: {{ request()->routeIs('admin.classes.*') ? 'true' : 'false' }},
                    instructors: {{ request()->routeIs('admin.instructors.*') ? 'true' : 'false' }},
                    members: {{ request()->routeIs('admin.members.*') ? 'true' : 'false' }},
                    finance: {{ request()->routeIs('admin.finance.*') ? 'true' : 'false' }},
                    events: {{ request()->routeIs('admin.events.*') ? 'true' : 'false' }},
                    communication: {{ request()->routeIs('admin.communication.*') ? 'true' : 'false' }},
                    reports: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }},
                    settings: {{ request()->routeIs('admin.system.*') || request()->routeIs('admin.settings.*') || request()->routeIs('admin.audit-logs.*') ? 'true' : 'false' }}
                }
            }">
                @if(hasRole('super_admin'))
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Branches -->
                    <div class="mt-1">
                        <button @click="openMenus.organization = !openMenus.organization" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dojos.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>Branches</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.organization ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.organization" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.dojos.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dojos.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Branch List
                            </a>
                            <a href="{{ route('admin.dojos.create') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dojos.create') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Add Branch
                            </a>
                        </div>
                    </div>

                    <!-- Users -->
                    <div class="mt-1">
                        <button @click="openMenus.users = !openMenus.users" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Users</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.users ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.users" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.index') && !request()->has('role') && !request()->has('filter') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                User List
                            </a>
                            <a href="{{ route('admin.users.create') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.create') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Add User
                            </a>
                        </div>
                    </div>

                    <!-- Curriculum -->
                    <div class="mt-1">
                        <button @click="openMenus.curriculum = !openMenus.curriculum" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.curriculum.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                <span>Curriculum</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.curriculum ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.curriculum" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.curriculum.styles') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.curriculum.styles') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Styles
                            </a>
                            <a href="{{ route('admin.curriculum.levels') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.curriculum.levels') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Levels
                            </a>
                            <a href="{{ route('admin.curriculum.belts') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.curriculum.belts') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Belts
                            </a>
                            <a href="{{ route('admin.curriculum.per-level') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.curriculum.per-level') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Curriculum per Level
                            </a>
                            <a href="{{ route('admin.curriculum.promotion-requirements') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.curriculum.promotion-requirements') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Promotion Requirements
                            </a>
                        </div>
                    </div>

                    <!-- Classes -->
                    <div class="mt-1">
                        <button @click="openMenus.classes = !openMenus.classes" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.classes.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span>Classes & Schedule</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.classes ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.classes" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.classes.templates') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.classes.templates') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Class Templates
                            </a>
                            <a href="{{ route('admin.classes.monitoring') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.classes.monitoring') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Schedule Monitoring
                            </a>
                            <a href="{{ route('admin.classes.calendar') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.classes.calendar') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Calendar
                            </a>
                        </div>
                    </div>

                    <!-- Instructors -->
                    <div class="mt-1">
                        <button @click="openMenus.instructors = !openMenus.instructors" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.instructors.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>Instructors</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.instructors ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.instructors" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.instructors.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.instructors.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Instructor List
                            </a>
                            <a href="{{ route('admin.instructors.certifications') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.instructors.certifications') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Certifications
                            </a>
                            <a href="{{ route('admin.instructors.performance') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.instructors.performance') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Performance
                            </a>
                        </div>
                    </div>

                    <!-- Members -->
                    <div class="mt-1">
                        <button @click="openMenus.members = !openMenus.members" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Students</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.members ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.members" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.members.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Student List
                            </a>
                            <a href="{{ route('admin.members.attendance-global') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.attendance-global') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Attendance
                            </a>
                            <a href="{{ route('admin.members.status') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.members.status') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Status
                            </a>
                        </div>
                    </div>

                    <!-- Finance -->
                    <div class="mt-1">
                        <button @click="openMenus.finance = !openMenus.finance" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.finance.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Finance</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.finance ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.finance" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.finance.payments') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.finance.payments') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Payments
                            </a>
                            <a href="{{ route('admin.finance.revenue-all') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.finance.revenue-all') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Revenue
                            </a>
                            <a href="{{ route('admin.finance.arrears') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.finance.arrears') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Arrears
                            </a>
                        </div>
                    </div>

                    <!-- Events -->
                    <div class="mt-1">
                        <button @click="openMenus.events = !openMenus.events" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Events</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.events ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.events" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.events.national') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.national') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                National Events
                            </a>
                            <a href="{{ route('admin.events.tournaments') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.tournaments') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Tournaments
                            </a>
                            <a href="{{ route('admin.events.grading') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.grading') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Grading
                            </a>
                            <a href="{{ route('admin.events.certificates') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.certificates') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Certificates
                            </a>
                        </div>
                    </div>

                    <!-- Communication -->
                    <div class="mt-1">
                        <button @click="openMenus.communication = !openMenus.communication" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.communication.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span>Communication</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.communication ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.communication" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.communication.announcements') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.communication.announcements') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Announcements
                            </a>
                            <a href="{{ route('admin.communication.broadcast') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.communication.broadcast') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Broadcast
                            </a>
                            <a href="{{ route('admin.communication.message-templates') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.communication.message-templates') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Message Templates
                            </a>
                        </div>
                    </div>

                    <!-- Reports -->
                    <div class="mt-1">
                        <button @click="openMenus.reports = !openMenus.reports" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Reports</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.reports ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.reports" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.reports.retention') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.retention') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Retention Rate
                            </a>
                            <a href="{{ route('admin.reports.popular-classes') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.popular-classes') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Popular Classes
                            </a>
                            <a href="{{ route('admin.reports.active-coaches') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.active-coaches') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Active Instructors
                            </a>
                            <a href="{{ route('admin.reports.events') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.events') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Event Reports
                            </a>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="mt-1">
                        <button @click="openMenus.settings = !openMenus.settings" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.system.*') || request()->routeIs('admin.settings.*') || request()->routeIs('admin.audit-logs.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Settings</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.settings ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.settings" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.system.settings') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.system.settings') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Roles & Permissions
                            </a>
                            <a href="{{ route('admin.audit-logs.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.audit-logs.*') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Audit Logs
                            </a>
                            <a href="{{ route('admin.settings.master-data') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.master-data') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Master Data
                            </a>
                            <a href="{{ route('admin.settings.whatsapp') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.whatsapp') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                WhatsApp Integration
                            </a>
                            <a href="{{ route('admin.settings.email') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.email') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Email Integration
                            </a>
                        </div>
                    </div>

                @elseif(hasRole('owner'))
                    <a href="{{ route('owner.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @elseif(hasRole('finance'))
                    <a href="{{ route('finance.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('finance.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @elseif(hasRole('coach'))
                    <a href="{{ route('coach.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('coach.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @elseif(hasRole('student'))
                    <a href="{{ route('student.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @elseif(hasRole('parent'))
                    <a href="{{ route('parent.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @endif
            </nav>
        </div>
    </div>
</aside>
@endauth
