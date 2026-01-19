@auth
@php
    // Check if user is a student by Member record (most reliable for students)
    $hasMemberRecord = \App\Models\Member::where('user_id', auth()->id())
                                         ->where('dojo_id', currentDojo())
                                         ->exists();
    
    // Check roles
    $hasStudentRoleWithDojo = hasRole('student', currentDojo());
    $hasStudentRoleNoDojo = hasRole('student');
    
    // Final determination - prioritize Member record
    $isStudent = $hasMemberRecord || $hasStudentRoleWithDojo || $hasStudentRoleNoDojo;
@endphp
<!-- Mobile Bottom Navigation - Always show for authenticated users -->
<div class="fixed bottom-0 left-0 right-0 z-[99999] bg-white border-t-2 border-gray-200 shadow-lg lg:hidden" style="padding-bottom: max(0.5rem, env(safe-area-inset-bottom));">
    <nav class="flex items-center justify-around w-full px-1 py-2" style="min-height: 60px;">
        @if($isStudent)
            <!-- Student Bottom Navigation (Purple Theme - 5 Items) -->
            <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center justify-center px-1 sm:px-2 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-[9px] sm:text-[10px] font-medium">Home</span>
            </a>

            <a href="{{ route('student.classes.index') }}" class="flex flex-col items-center justify-center px-1 sm:px-2 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('student.classes.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="text-[9px] sm:text-[10px] font-medium">Classes</span>
            </a>

            <a href="{{ route('student.progress.index') }}" class="flex flex-col items-center justify-center px-1 sm:px-2 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('student.progress.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                <span class="text-[9px] sm:text-[10px] font-medium">Belt</span>
            </a>

            <a href="{{ route('student.announcements.index') }}" class="flex flex-col items-center justify-center px-1 sm:px-2 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('student.announcements.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="text-[9px] sm:text-[10px] font-medium">News</span>
            </a>

            <a href="{{ route('student.payments.index') }}" class="flex flex-col items-center justify-center px-1 sm:px-2 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('student.payments.*') ? 'text-purple-600 bg-purple-50' : 'text-gray-600 hover:text-purple-600 hover:bg-gray-50' }}">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-[9px] sm:text-[10px] font-medium">Payments</span>
            </a>
        @else
            <!-- Parent Bottom Navigation -->
            <a href="{{ route('parent.dashboard') }}" class="flex flex-col items-center justify-center px-2 sm:px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('parent.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-[10px] sm:text-xs font-medium">Dashboard</span>
            </a>

            <a href="{{ route('parent.children.index') }}" class="flex flex-col items-center justify-center px-2 sm:px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('parent.children.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="text-[10px] sm:text-xs font-medium">Children</span>
            </a>

            <a href="{{ route('parent.schedules.index') }}" class="flex flex-col items-center justify-center px-2 sm:px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('parent.schedules.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-[10px] sm:text-xs font-medium">Schedule</span>
            </a>

            <a href="{{ route('parent.events.index') }}" class="flex flex-col items-center justify-center px-2 sm:px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('parent.events.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-[10px] sm:text-xs font-medium">Events</span>
            </a>

            <a href="{{ route('parent.payments.index') }}" class="flex flex-col items-center justify-center px-2 sm:px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('parent.payments.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                <span class="text-[10px] sm:text-xs font-medium">Payments</span>
            </a>
        @endif
    </nav>
</div>
@endauth
