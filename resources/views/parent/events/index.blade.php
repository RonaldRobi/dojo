<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Events
                </h2>
                <p class="text-sm text-gray-600 mt-1">View and register your children for upcoming events</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 space-y-4 sm:space-y-6">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($children->count() > 0)
                <!-- Registered Events -->
                @if($registrations->count() > 0)
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-4 sm:px-6 py-4 sm:py-5">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                    <i class="fas fa-check-circle text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Registered Events</h3>
                                    <p class="text-sm text-green-100">Your children's event registrations</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($registrations as $registration)
                                    <div class="border-l-4 border-green-500 bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-r-lg hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h4 class="text-lg font-bold text-gray-900">{{ $registration->event->name ?? 'N/A' }}</h4>
                                                </div>
                                                <p class="text-xs text-gray-500 mb-1">Child: <span class="font-semibold">{{ $registration->member->name }}</span></p>
                                                <p class="text-xs text-gray-500 mb-3">Dojo: <span class="font-semibold">{{ $registration->event->dojo->name ?? 'N/A' }}</span></p>
                                                <p class="text-sm text-gray-600 mb-3">{{ $registration->event->description ?? 'No description' }}</p>
                                                <div class="space-y-2">
                                                    <div class="flex items-center text-sm text-gray-700">
                                                        <i class="fas fa-calendar mr-2 text-green-600"></i>
                                                        {{ $registration->event->event_date->format('F d, Y • g:i A') }}
                                                    </div>
                                                    @if($registration->event->location)
                                                        <div class="flex items-center text-sm text-gray-700">
                                                            <i class="fas fa-map-marker-alt mr-2 text-green-600"></i>
                                                            {{ $registration->event->location }}
                                                        </div>
                                                    @endif
                                                    <div class="flex items-center text-sm text-gray-700">
                                                        <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                                        Status: <span class="ml-1 font-semibold {{ $registration->status == 'confirmed' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($registration->status) }}</span>
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-700">
                                                        <i class="fas fa-credit-card mr-2 text-green-600"></i>
                                                        Payment: <span class="ml-1 font-semibold {{ $registration->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">{{ ucfirst($registration->payment_status) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ route('parent.events.show', $registration->event->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-semibold">
                                                View Details
                                                <i class="fas fa-arrow-right ml-2"></i>
                                            </a>
                                            @if($registration->payment_status == 'pending' && $registration->invoice)
                                                <a href="{{ route('parent.payments.show', $registration->invoice->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-semibold">
                                                    <i class="fas fa-credit-card mr-2"></i>
                                                    Pay Now
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Upcoming Events -->
                @if($allEvents->count() > 0)
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-pink-700 px-4 sm:px-6 py-4 sm:py-5">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Upcoming Events</h3>
                                    <p class="text-sm text-purple-100">Available events from your children's dojos</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($allEvents as $event)
                                    <div class="border-l-4 border-purple-500 bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-r-lg hover:shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $event->name }}</h4>
                                                <p class="text-xs text-gray-500 mb-3">Dojo: <span class="font-semibold">{{ $event->dojo->name ?? 'N/A' }}</span></p>
                                                <p class="text-sm text-gray-600 mb-3">{{ $event->description ?? 'No description' }}</p>
                                                <div class="space-y-2">
                                                    <div class="flex items-center text-sm text-gray-700">
                                                        <i class="fas fa-calendar mr-2 text-purple-600"></i>
                                                        {{ $event->event_date->format('F d, Y • g:i A') }}
                                                    </div>
                                                    @if($event->location)
                                                        <div class="flex items-center text-sm text-gray-700">
                                                            <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>
                                                            {{ $event->location }}
                                                        </div>
                                                    @endif
                                                    @if($event->registration_fee)
                                                        <div class="flex items-center text-sm text-gray-700">
                                                            <i class="fas fa-dollar-sign mr-2 text-purple-600"></i>
                                                            Fee: RM {{ number_format($event->registration_fee, 0) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('parent.events.show', $event->id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-semibold">
                                            View & Register
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($registrations->count() == 0 && $allEvents->count() == 0)
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-8 sm:p-12 text-center">
                        <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-alt text-gray-400" style="font-size: 3rem;"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No events available</h3>
                        <p class="text-gray-600">There are no events available at this time.</p>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-8 sm:p-12 text-center">
                    <div class="mx-auto h-20 w-20 sm:h-24 sm:w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-users text-gray-400 text-5xl"></i>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">No children linked</h3>
                    <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Register your child to view events.</p>
                    <a href="{{ route('parent.register.create') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm sm:text-base font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Register Child
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

