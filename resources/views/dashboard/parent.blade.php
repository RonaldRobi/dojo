<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Parent Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">Monitor your children's progress and activities</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Children Selector -->
            @if($stats['children']->count() > 1)
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="flex items-center space-x-4 overflow-x-auto">
                        @foreach($stats['children'] as $child)
                            <a href="?child_id={{ $child->id }}" class="flex-shrink-0 px-6 py-4 rounded-xl {{ $stats['selected_child'] && $stats['selected_child']->id === $child->id ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all duration-200 transform hover:scale-105">
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $child->name }}</div>
                                    @if($child->currentBelt)
                                        <div class="text-xs mt-1 opacity-80">{{ $child->currentBelt->belt->name ?? 'No Belt' }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($stats['selected_child'])
                <!-- Selected Child Info -->
                <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
                    <div class="p-8 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-3xl font-bold mb-2">{{ $stats['selected_child']->name }}</h3>
                                <p class="text-indigo-100 mb-4">{{ $stats['selected_child']->dojo->name ?? 'N/A' }}</p>
                                @if($stats['selected_child_progress'])
                                    <div class="flex items-center space-x-3">
                                        <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-full font-semibold text-lg">
                                            {{ $stats['selected_child_progress']->belt->name ?? 'No Belt' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <div class="p-6 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl">
                                    <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Enrolled Classes -->
                    <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                        <div class="relative p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Enrolled Classes</p>
                                    <p class="mt-2 text-4xl font-bold text-white">{{ $stats['selected_child_total_classes'] }}</p>
                                </div>
                                <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Attendance -->
                    <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                        <div class="relative p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium uppercase tracking-wide">This Month</p>
                                    <p class="mt-2 text-4xl font-bold text-white">{{ $stats['selected_child_attendance'] }}</p>
                                    <p class="mt-1 text-xs text-green-100">Classes attended</p>
                                </div>
                                <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 transition-transform duration-300">
                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Belt -->
                    <div class="group relative bg-gradient-to-br from-yellow-500 to-amber-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                        <div class="relative p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-medium uppercase tracking-wide">Current Belt</p>
                                    <p class="mt-2 text-2xl font-bold text-white">{{ $stats['selected_child_progress']->belt->name ?? 'White Belt' }}</p>
                                    <p class="mt-1 text-xs text-yellow-100">Current rank</p>
                                </div>
                                <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                @if($stats['pending_payments']->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                        <div class="bg-gradient-to-r from-yellow-600 to-amber-700 px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Pending Payments</h3>
                                    <p class="text-sm text-yellow-100">{{ $stats['pending_payments']->count() }} invoices awaiting payment</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($stats['pending_payments'] as $invoice)
                                    <div class="flex items-center justify-between p-5 bg-yellow-50 rounded-xl border-l-4 border-yellow-500 hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">Invoice #{{ $invoice->invoice_number }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">Due: {{ $invoice->due_date->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-yellow-600">RM {{ number_format($invoice->total_amount, 2) }}</p>
                                            <span class="inline-block px-3 py-1 mt-2 text-xs font-semibold bg-yellow-200 text-yellow-800 rounded-full">Pending</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upcoming Events -->
                @if($stats['upcoming_events']->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-700 px-6 py-5">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Upcoming Events</h3>
                                    <p class="text-sm text-purple-100">Events your child is registered for</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($stats['upcoming_events'] as $eventReg)
                                    <div class="flex items-center justify-between p-5 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border-l-4 border-purple-500 hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                        <div>
                                            <h4 class="text-lg font-bold text-gray-900">{{ $eventReg->event->name ?? 'N/A' }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $eventReg->event->description ?? 'No description' }}</p>
                                            <p class="text-xs text-purple-600 font-medium mt-2">
                                                {{ $eventReg->event->event_date->format('F d, Y • g:i A') }}
                                            </p>
                                        </div>
                                        <div class="p-4 bg-purple-500 rounded-xl">
                                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No children linked</h3>
                    <p class="text-gray-600">Contact your dojo administrator to link your children's accounts</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

