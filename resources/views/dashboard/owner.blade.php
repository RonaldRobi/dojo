<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $stats['dojo_name'] ?? 'Owner Dashboard' }}</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $stats['dojo_address'] ?? 'Manage your branch operations' }}</p>
            </div>
        </div>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Members -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6">
            <div class="flex items-center justify-between text-white">
                <div>
                    <p class="text-blue-100 text-sm font-medium uppercase">Total Members</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['total_members'] }}</p>
                    <p class="text-blue-100 text-xs mt-1">{{ $stats['active_members'] }} active</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Schedules -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6">
            <div class="flex items-center justify-between text-white">
                <div>
                    <p class="text-green-100 text-sm font-medium uppercase">Schedules</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['active_schedules'] }}</p>
                    <p class="text-green-100 text-xs mt-1">{{ $stats['total_schedules'] }} total</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6">
            <div class="flex items-center justify-between text-white">
                <div>
                    <p class="text-purple-100 text-sm font-medium uppercase">Total Revenue</p>
                    <p class="text-3xl font-bold mt-2">RM {{ number_format($stats['total_revenue'], 0) }}</p>
                    <p class="text-purple-100 text-xs mt-1">{{ $stats['pending_invoices'] }} pending</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg hover:shadow-xl transition-shadow p-6">
            <div class="flex items-center justify-between text-white">
                <div>
                    <p class="text-orange-100 text-sm font-medium uppercase">Upcoming Events</p>
                    <p class="text-3xl font-bold mt-2">{{ $stats['upcoming_events'] }}</p>
                    <p class="text-orange-100 text-xs mt-1">Events scheduled</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Instructors</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_instructors'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">New Members</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['new_members_this_month'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Today Attendance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today_attendance'] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Pending Amount</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">RM {{ number_format($stats['pending_amount'], 0) }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Members</h3>
                    <a href="{{ route('owner.members.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">View All →</a>
                </div>
            </div>
            <div class="p-6">
                @if($recentMembers->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentMembers as $member)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-purple-600 font-semibold text-sm">{{ substr($member->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                        <p class="text-xs text-gray-500">Joined: {{ \Carbon\Carbon::parse($member->join_date)->format('d M Y') }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm text-center py-8">No recent members</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                    <a href="{{ route('owner.events.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">View All →</a>
                </div>
            </div>
            <div class="p-6">
                @if($upcomingEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingEvents as $event)
                            <div class="flex items-start py-3 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-lg flex flex-col items-center justify-center">
                                    <span class="text-xs text-orange-600 font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                                    <span class="text-lg font-bold text-orange-600">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $event->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($event->type) }} • {{ $event->location ?? 'TBA' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm text-center py-8">No upcoming events</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('owner.schedules.create') }}" class="flex flex-col items-center gap-2 px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                <svg class="w-6 h-6 text-green-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs font-medium text-gray-700 text-center">New Schedule</span>
            </a>
            <a href="{{ route('owner.members.attendance') }}" class="flex flex-col items-center gap-2 px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                <svg class="w-6 h-6 text-purple-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <span class="text-xs font-medium text-gray-700 text-center">Attendance</span>
            </a>
            <a href="{{ route('owner.events.create') }}" class="flex flex-col items-center gap-2 px-4 py-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors group">
                <svg class="w-6 h-6 text-orange-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"></path>
                </svg>
                <span class="text-xs font-medium text-gray-700 text-center">New Event</span>
            </a>
            <a href="{{ route('owner.announcements.create') }}" class="flex flex-col items-center gap-2 px-4 py-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors group">
                <svg class="w-6 h-6 text-yellow-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                </svg>
                <span class="text-xs font-medium text-gray-700 text-center">Announce</span>
            </a>
            <a href="{{ route('owner.reports.revenue') }}" class="flex flex-col items-center gap-2 px-4 py-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors group">
                <svg class="w-6 h-6 text-red-600 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="text-xs font-medium text-gray-700 text-center">View Reports</span>
            </a>
        </div>
    </div>
</x-app-layout>
