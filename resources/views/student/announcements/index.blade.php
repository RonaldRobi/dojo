<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Announcements
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Stay updated with the latest news</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($announcements && $announcements->count() > 0)
                <div class="space-y-4 sm:space-y-6">
                    @foreach($announcements as $announcement)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 transform transition-all duration-200 hover:shadow-2xl">
                            <div class="p-4 sm:p-6">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1">
                                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">{{ $announcement->title }}</h3>
                                        <div class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-gray-500">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $announcement->created_at->format('F d, Y') }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                {{ $announcement->dojo->name ?? 'Dojo Admin' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm sm:text-base text-gray-700 mb-4 line-clamp-3">{{ $announcement->content }}</p>
                                <div class="flex justify-end">
                                    <a href="{{ route('student.announcements.show', $announcement->id) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                        Read More
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $announcements->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-blue-100 mb-4">
                            <svg class="h-10 w-10 sm:h-12 sm:w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No Announcements Yet</h3>
                        <p class="text-sm sm:text-base text-gray-600">Check back later for updates and announcements from your dojo.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

