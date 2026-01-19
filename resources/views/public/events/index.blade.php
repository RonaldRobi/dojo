<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Events - Droplets Dojo</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <svg class="h-10 w-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">Droplets Dojo</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-blue-700 transition">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">Upcoming Events</h1>
            <p class="text-xl text-blue-100">Join our exciting martial arts events and competitions</p>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        @if($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        @if($event->event_image)
                            <img src="{{ asset('storage/' . $event->event_image) }}" alt="{{ $event->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center">
                                <svg class="h-20 w-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ ucfirst($event->type) }}</span>
                                @if($event->registration_fee > 0)
                                    <span class="text-sm font-semibold text-gray-900">RM {{ number_format($event->registration_fee, 0) }}</span>
                                @else
                                    <span class="text-sm font-semibold text-green-600">Free</span>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $event->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description ?? '', 100) }}</p>
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $event->event_date->format('M d, Y') }}
                                </div>
                                @if($event->location)
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->location }}
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('public.events.show', $event) }}" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No events</h3>
                <p class="mt-1 text-sm text-gray-500">There are no upcoming events at this time.</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} Droplets Dojo. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
