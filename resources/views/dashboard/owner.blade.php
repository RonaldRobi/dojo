<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Owner Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your dojo operations and activities</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid dengan animasi dan gradient -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Active Members -->
                <div class="group relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Active Members</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['active_members'] ?? 0 }}</p>
                                <p class="mt-1 text-xs text-blue-100">Currently active</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Classes -->
                <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium uppercase tracking-wide">Total Classes</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['total_classes'] ?? 0 }}</p>
                                <p class="mt-1 text-xs text-green-100">All classes</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Classes -->
                <div class="group relative bg-gradient-to-br from-yellow-500 to-amber-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium uppercase tracking-wide">Active Classes</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['active_classes'] ?? 0 }}</p>
                                <p class="mt-1 text-xs text-yellow-100">Currently running</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Invoices -->
                <div class="group relative bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-medium uppercase tracking-wide">Pending Invoices</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['pending_invoices'] ?? 0 }}</p>
                                <p class="mt-1 text-xs text-red-100">Awaiting payment</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats dengan gradient cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Retention Rate -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium uppercase tracking-wide">Retention Rate</p>
                            <p class="mt-4 text-5xl font-bold text-white">{{ number_format(($stats['retention_rate']['retention_rate'] ?? 0), 1) }}%</p>
                            <p class="mt-2 text-sm text-indigo-100">Member retention over last period</p>
                        </div>
                        <div class="p-6 bg-white bg-opacity-20 rounded-2xl backdrop-blur-sm">
                            <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Overdue Invoices -->
                <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl hover:scale-105">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium uppercase tracking-wide">Overdue Invoices</p>
                            <p class="mt-4 text-5xl font-bold text-white">{{ $stats['overdue_invoices'] ?? 0 }}</p>
                            <p class="mt-2 text-sm text-red-100">Requires immediate attention</p>
                        </div>
                        <div class="p-6 bg-white bg-opacity-20 rounded-2xl backdrop-blur-sm">
                            <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions dengan hover effects -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('owner.members.create') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-blue-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-8">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-4 bg-blue-100 group-hover:bg-white rounded-xl transition-colors duration-300">
                                <svg class="h-10 w-10 text-blue-600 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-white transition-colors duration-300">Add New Member</h3>
                        <p class="text-sm text-gray-600 group-hover:text-blue-100 mt-2 transition-colors duration-300">Register a new student</p>
                    </div>
                </a>

                <a href="{{ route('owner.classes.create') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-8">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-4 bg-green-100 group-hover:bg-white rounded-xl transition-colors duration-300">
                                <svg class="h-10 w-10 text-green-600 group-hover:text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-white transition-colors duration-300">Create Class</h3>
                        <p class="text-sm text-gray-600 group-hover:text-green-100 mt-2 transition-colors duration-300">Add a new class schedule</p>
                    </div>
                </a>

                <a href="{{ route('owner.events.create') }}" class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-500 to-amber-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative p-8">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-4 bg-yellow-100 group-hover:bg-white rounded-xl transition-colors duration-300">
                                <svg class="h-10 w-10 text-yellow-600 group-hover:text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-white transition-colors duration-300">New Event</h3>
                        <p class="text-sm text-gray-600 group-hover:text-yellow-100 mt-2 transition-colors duration-300">Create an event</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
