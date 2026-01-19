<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Announcement
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $announcement->created_at->format('F d, Y') }}</p>
            </div>
            <a href="{{ route('student.announcements.index') }}" 
               class="hidden sm:inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white rounded-xl shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-4 sm:px-6 py-6 sm:py-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 p-3 sm:p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm">
                            <svg class="h-8 w-8 sm:h-10 sm:w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-3">{{ $announcement->title }}</h1>
                            <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-blue-100">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $announcement->created_at->format('F d, Y • g:i A') }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    By {{ $announcement->dojo->name ?? 'Dojo Admin' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8">
                    <div class="prose prose-sm sm:prose lg:prose-lg max-w-none">
                        <div class="text-base sm:text-lg text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $announcement->content }}
                        </div>
                    </div>

                    @if($announcement->attachments)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-3">Attachments</h3>
                            <div class="space-y-2">
                                <!-- Add attachment handling here if needed -->
                            </div>
                        </div>
                    @endif
                </div>
            </article>

            <!-- Back Button (Mobile) -->
            <div class="mt-6 sm:hidden">
                <a href="{{ route('student.announcements.index') }}" 
                   class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Announcements
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

