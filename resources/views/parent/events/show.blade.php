<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900">
                {{ $event->name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">Event details and registration</p>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-4xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 space-y-4 sm:space-y-6">
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

            <div class="mb-6">
                <a href="{{ route('parent.events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Events
                </a>
            </div>

            <!-- Event Header -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-700 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-8 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-3xl font-bold mb-2">{{ $event->name }}</h3>
                            <p class="text-purple-100 text-lg">{{ $event->description ?? 'No description available' }}</p>
                        </div>
                        <div class="p-6 bg-white bg-opacity-20 rounded-2xl backdrop-blur-sm">
                            <i class="fas fa-calendar-check text-white" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Event Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-calendar mr-3 text-purple-600 mt-1 text-xl"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Date & Time</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $event->event_date->format('F d, Y • g:i A') }}</p>
                            </div>
                        </div>
                        @if($event->location)
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt mr-3 text-purple-600 mt-1 text-xl"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Location</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $event->location }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @if($event->registration_fee)
                            <div class="flex items-start">
                                <i class="fas fa-dollar-sign mr-3 text-purple-600 mt-1 text-xl"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Registration Fee</p>
                                    <p class="text-lg font-semibold text-gray-900">RM {{ number_format($event->registration_fee, 0) }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-start">
                            <i class="fas fa-tag mr-3 text-purple-600 mt-1 text-xl"></i>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Event Type</p>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($event->event_type ?? 'General') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Children Registrations -->
            @if($children->count() > 0)
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Register Your Children</h3>
                    <div class="space-y-4">
                        @foreach($children as $child)
                            @php
                                $registration = $registrations->firstWhere('member_id', $child->id);
                            @endphp
                            <div class="border-l-4 {{ $registration ? 'border-green-500 bg-green-50' : 'border-purple-500 bg-purple-50' }} p-6 rounded-r-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-900 mb-2">{{ $child->name }}</h4>
                                        <p class="text-xs text-gray-500 mb-2">Dojo: <span class="font-semibold">{{ $child->dojo->name ?? 'N/A' }}</span></p>
                                        @if($registration)
                                            <div class="space-y-2">
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-semibold">Status:</span> 
                                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">{{ ucfirst($registration->status) }}</span>
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-semibold">Payment:</span> 
                                                    <span class="px-2 py-1 {{ $registration->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} rounded-full text-xs font-semibold">{{ ucfirst($registration->payment_status) }}</span>
                                                </p>
                                                @if($registration->registered_at)
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-semibold">Registered:</span> {{ $registration->registered_at->format('F d, Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-600 mb-3">Not registered for this event</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        @if($registration)
                                            <div class="p-3 bg-green-100 rounded-lg">
                                                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                                            </div>
                                            @if($registration->payment_status == 'pending' && $registration->invoice)
                                                <a href="{{ route('parent.payments.show', $registration->invoice->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm font-semibold">
                                                    <i class="fas fa-credit-card mr-2"></i>
                                                    Pay Now
                                                </a>
                                            @endif
                                        @else
                                            <form action="{{ route('parent.events.register', $event->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="member_id" value="{{ $child->id }}">
                                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200 transform hover:scale-105 shadow-lg font-semibold">
                                                    <i class="fas fa-user-plus mr-2"></i>
                                                    Register {{ $child->name }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                    <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-users text-gray-400" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No children linked</h3>
                    <p class="text-gray-600 mb-6">Register your child to participate in events.</p>
                    <a href="{{ route('parent.register.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Register Child
                    </a>
                </div>
            @endif

            <!-- Additional Information -->
            @if($event->additional_info)
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Additional Information</h3>
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($event->additional_info)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

