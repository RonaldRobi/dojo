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

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 space-y-4 sm:space-y-6 lg:space-y-8">
            
            <!-- No Children Message -->
            @if($stats['children']->isEmpty())
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-2xl overflow-hidden">
                    <div class="p-12 text-center text-white">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-white bg-opacity-20 rounded-full mb-6">
                            <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-4xl font-bold mb-4">Welcome to Droplets Dojo!</h3>
                        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                            Get started by registering your children to begin their martial arts journey.
                        </p>
                        <a href="{{ route('parent.children.index') }}" class="inline-flex items-center px-8 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition-all duration-200 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Register Your Children
                        </a>
                    </div>
                </div>

                <!-- Feature Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 text-center transition-all duration-300 transform hover:-translate-y-2">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Register Children</h4>
                        <p class="text-sm text-gray-600">Easily add your children and manage their profiles</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 text-center transition-all duration-300 transform hover:-translate-y-2">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Track Progress</h4>
                        <p class="text-sm text-gray-600">Monitor attendance, belt progress & achievements</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl p-8 text-center transition-all duration-300 transform hover:-translate-y-2">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-xl mb-4 shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2">Manage Payments</h4>
                        <p class="text-sm text-gray-600">View invoices and make payments online</p>
                    </div>
                </div>
            @endif
            
            <!-- Children Selector -->
            @if($stats['children']->count() > 1)
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-4 sm:p-6">
                    <div class="flex items-center space-x-4 overflow-x-auto">
                        @foreach($stats['children'] as $child)
                            <a href="?child_id={{ $child->id }}" class="flex-shrink-0 px-6 py-4 rounded-xl {{ $stats['selected_child'] && $stats['selected_child']->id === $child->id ? 'bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-all duration-200 transform hover:scale-105">
                                <div class="text-center">
                                    <div class="text-2xl font-bold">{{ $child->name }}</div>
                                    @if($child->currentBelt)
                                        <div class="text-xs mt-1 opacity-80">{{ $child->currentBelt->name ?? 'No Belt' }}</div>
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
                                    <i class="fas fa-user text-6xl text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
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
                                    <i class="fas fa-book text-4xl text-white"></i>
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
                                    <i class="fas fa-check-circle text-4xl text-white"></i>
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
                                    <p class="mt-2 text-2xl font-bold text-white">{{ $stats['selected_child_progress']->name ?? 'White Belt' }}</p>
                                    <p class="mt-1 text-xs text-yellow-100">Current rank</p>
                                </div>
                                <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-medal text-4xl text-white"></i>
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
                                    <i class="fas fa-dollar-sign text-2xl text-white"></i>
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
                                            <p class="text-2xl font-bold text-yellow-600">RM {{ number_format($invoice->total_amount, 0) }}</p>
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
                                    <i class="fas fa-calendar-alt text-2xl text-white"></i>
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
                                            <i class="fas fa-calendar-check text-3xl text-white"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>

