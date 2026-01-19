@auth
<aside class="hidden lg:flex lg:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex flex-col flex-grow bg-sidebar overflow-y-auto">
            <!-- Logo Section -->
            <div class="flex items-center flex-shrink-0 px-6 py-5 border-b border-sidebar-700">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center shadow-lg overflow-hidden bg-white p-1">
                            <img src="{{ asset('storage/logo.png') }}" alt="Droplets Dojo Logo" class="h-full w-full object-contain">
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
                    settings: {{ request()->routeIs('admin.system.*') || request()->routeIs('admin.settings.*') || request()->routeIs('admin.audit-logs.*') ? 'true' : 'false' }},
                    parentChildren: {{ request()->routeIs('parent.children.*') ? 'true' : 'false' }},
                    parentEvents: {{ request()->routeIs('parent.events.*') ? 'true' : 'false' }},
                    parentPayments: {{ request()->routeIs('parent.payments.*') ? 'true' : 'false' }},
                    coachStudents: {{ request()->routeIs('coach.students.*') ? 'true' : 'false' }},
                    coachEvents: {{ request()->routeIs('coach.events.*') ? 'true' : 'false' }},
                    coachProgress: {{ request()->routeIs('coach.progress.*') ? 'true' : 'false' }},
                    coachClasses: {{ request()->routeIs('coach.classes.*') ? 'true' : 'false' }}
                }
            }">
                @php
                    // Check if user is a student - either by role OR by having a Member record
                    $isStudent = hasRole('student', currentDojo()) || 
                                 hasRole('student') || 
                                 \App\Models\Member::where('user_id', auth()->id())
                                                   ->where('dojo_id', currentDojo())
                                                   ->exists();
                @endphp
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

                    <!-- Weekly Schedules -->
                    <div class="mt-1">
                        <button @click="openMenus.classes = !openMenus.classes" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.classes.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Weekly Schedules</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.classes ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.classes" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('admin.classes.monitoring') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.classes.monitoring') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Create Schedule
                            </a>
                            <a href="{{ route('admin.classes.calendar') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.classes.calendar') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                View Schedules
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
                   <a href="{{ route('admin.events.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.index') || request()->routeIs('admin.events.show') || request()->routeIs('admin.events.edit') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                       All Events
                   </a>
                   <a href="{{ route('admin.events.create') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.events.create') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                       Create Event
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
                            <a href="{{ route('admin.reports.revenue') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.revenue') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Revenue
                            </a>
                            <a href="{{ route('admin.reports.events') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.events') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Event Reports
                            </a>
                        </div>
                    </div>

                    <!-- Pricing -->
                    <div class="mt-1">
                        <a href="{{ route('admin.pricing.index') }}" class="group flex items-center w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.pricing.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pricing</span>
                        </a>
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
                        </div>
                    </div>

                @elseif(hasRole('owner'))
                    <!-- Dashboard -->
                    <a href="{{ route('owner.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Members (Students) -->
                    <div class="mt-1">
                        <button @click="openMenus.members = !openMenus.members" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.members.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Members</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.members ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.members" x-transition class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('owner.members.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.members.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                All Members
                            </a>
                            <a href="{{ route('owner.members.attendance') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.members.attendance') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Attendance
                            </a>
                        </div>
                    </div>

                    <!-- Schedules -->
                    <a href="{{ route('owner.schedules.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.schedules.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Class Schedules</span>
                    </a>

                    <!-- Instructors -->
                    <a href="{{ route('owner.instructors.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.instructors.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Instructors</span>
                    </a>

                    <!-- Invoices -->
                    <a href="{{ route('owner.invoices.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.invoices.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Invoices</span>
                    </a>

                    <!-- Events -->
                    <a href="{{ route('owner.events.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.events.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Events</span>
                    </a>

                    <!-- Communication -->
                    <a href="{{ route('owner.announcements.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.announcements.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        <span>Announcements</span>
                    </a>

                    <!-- Reports -->
                    <div class="mt-1">
                        <button @click="openMenus.reports = !openMenus.reports" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.reports.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
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
                        <div x-show="openMenus.reports" x-transition class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('owner.reports.revenue') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.reports.revenue') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Revenue
                            </a>
                            <a href="{{ route('owner.reports.events') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('owner.reports.events') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Event Reports
                            </a>
                        </div>
                    </div>
                @elseif(hasRole('coach'))
                    <!-- Dashboard -->
                    <a href="{{ route('coach.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('coach.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Classes -->
                    <div class="mt-1">
                        <a href="{{ route('coach.classes.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('coach.classes.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span>My Classes</span>
                        </a>
                    </div>

                    <!-- Students -->
                    <div class="mt-1">
                        <a href="{{ route('coach.students.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('coach.students.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span>My Students</span>
                        </a>
                    </div>

                    <!-- Progress -->
                    <div class="mt-1">
                        <a href="{{ route('coach.progress.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('coach.progress.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            <span>Student Progress</span>
                        </a>
                    </div>

                    <!-- Events -->
                    <div class="mt-1">
                        <a href="{{ route('coach.events.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('coach.events.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Events</span>
                        </a>
                    </div>

                    <!-- Broadcasting -->
                    <div class="mt-1">
                        <a href="{{ route('coach.broadcasting.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('coach.broadcasting.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            <span>Broadcast Message</span>
                        </a>
                    </div>
                @elseif($isStudent)
                    <!-- Home -->
                    <a href="{{ route('student.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Home</span>
                    </a>

                    <!-- My Classes -->
                    <a href="{{ route('student.classes.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.classes.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>My Classes</span>
                    </a>

                    <!-- My Belt Progress -->
                    <a href="{{ route('student.progress.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.progress.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <span>My Belt Progress</span>
                    </a>

                    <!-- News & Updates -->
                    <a href="{{ route('student.announcements.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.announcements.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span>News & Updates</span>
                    </a>

                    <!-- Payments -->
                    <a href="{{ route('student.payments.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.payments.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Payments</span>
                    </a>
                @elseif(hasRole('parent'))
                    <!-- Dashboard -->
                    <a href="{{ route('parent.dashboard') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.dashboard') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- My Children -->
                    <div class="mt-1">
                        <button @click="openMenus.parentChildren = !openMenus.parentChildren" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.children.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>My Children</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.parentChildren ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.parentChildren" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('parent.children.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.children.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Children List
                            </a>
                            <a href="{{ route('parent.register.create') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.register.create') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Register Child
                            </a>
                        </div>
                    </div>

                    <!-- Schedules -->
                    <a href="{{ route('parent.schedules.index') }}" class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.schedules.*') ? 'bg-purple-600 text-white shadow-lg' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Class Schedules</span>
                    </a>

                    <!-- Events -->
                    <div class="mt-1">
                        <button @click="openMenus.parentEvents = !openMenus.parentEvents" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.events.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Events</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.parentEvents ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.parentEvents" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('parent.events.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.events.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                All Events
                            </a>
                        </div>
                    </div>

                    <!-- Payments -->
                    <div class="mt-1">
                        <button @click="openMenus.parentPayments = !openMenus.parentPayments" class="group flex items-center justify-between w-full px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.payments.*') ? 'bg-sidebar-600 text-white' : 'text-gray-300 hover:bg-sidebar-600 hover:text-white' }}">
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Payments</span>
                            </div>
                            <svg class="h-4 w-4 transition-transform duration-200 flex-shrink-0" :class="openMenus.parentPayments ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.parentPayments" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="mt-1 ml-4 space-y-1 border-l-2 border-sidebar-600 pl-3">
                            <a href="{{ route('parent.payments.index') }}" class="flex items-center px-3 py-2 text-xs font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('parent.payments.index') ? 'bg-purple-600 text-white' : 'text-gray-400 hover:bg-sidebar-600 hover:text-white' }}">
                                Payment History
                            </a>
                        </div>
                    </div>
                @endif
            </nav>
        </div>
    </div>
</aside>
@endauth
