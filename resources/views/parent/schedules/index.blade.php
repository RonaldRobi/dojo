<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Weekly Schedule
                </h2>
                <p class="text-sm text-gray-600 mt-1">View all your children's class schedules for the week</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
            @if($children->count() > 0)
                <!-- Children Info Header -->
                <div class="mb-4 sm:mb-6 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-4 sm:p-6">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-white">All Children's Schedule</h3>
                            <p class="text-blue-100 mt-1">{{ $children->count() }} {{ $children->count() === 1 ? 'child' : 'children' }} enrolled</p>
                        </div>
                        <div class="flex items-center space-x-3 flex-wrap gap-2">
                            @foreach($children as $child)
                                <div class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                    <div class="w-2 h-2 rounded-full mr-2" style="background-color: {{ '#' . substr(md5($child->id), 0, 6) }};"></div>
                                    <span class="text-white font-semibold text-sm">{{ $child->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Weekly Calendar View -->
                @if(!empty($weeklySchedules))
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($weeklySchedules as $dayData)
                            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                                <!-- Day Header -->
                                <div class="bg-gradient-to-r from-purple-500 to-pink-600 px-4 sm:px-6 py-3 sm:py-4">
                                    <h3 class="text-lg sm:text-xl font-bold text-white">{{ $dayData['day'] }}</h3>
                                    <p class="text-purple-100 text-sm">{{ count($dayData['schedules']) }} {{ count($dayData['schedules']) === 1 ? 'class' : 'classes' }}</p>
                                </div>

                                <!-- Schedules List -->
                                <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                                    @forelse($dayData['schedules'] as $item)
                                        <div class="border-l-4 p-4 rounded-r-lg hover:shadow-md transition-all duration-200"
                                             style="border-color: {{ '#' . substr(md5($item['child']->id), 0, 6) }}; background-color: {{ '#' . substr(md5($item['child']->id), 0, 6) }}15;">
                                            <!-- Child Name Badge -->
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                                     style="background-color: {{ '#' . substr(md5($item['child']->id), 0, 6) }}25; color: {{ '#' . substr(md5($item['child']->id), 0, 6) }};">
                                                    <div class="w-2 h-2 rounded-full mr-2" style="background-color: {{ '#' . substr(md5($item['child']->id), 0, 6) }};"></div>
                                                    {{ $item['child']->name }}
                                                </div>
                                                <span class="px-2 py-1 {{ $item['schedule']->dojo ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-600' }} text-xs font-semibold rounded">
                                                    {{ $item['schedule']->dojo->name ?? 'Dojo Undefined' }}
                                                </span>
                                            </div>

                                            <!-- Class Info -->
                                            <h4 class="text-base font-bold text-gray-900 mb-2">
                                                {{ $item['schedule']->class_name ?? 'Class' }}
                                            </h4>
                                            
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ date('g:i A', strtotime($item['schedule']->start_time)) }} - {{ date('g:i A', strtotime($item['schedule']->end_time)) }}
                                                </div>
                                                
                                                @if($item['schedule']->instructor)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                        {{ $item['schedule']->instructor->name }}
                                                    </div>
                                                @endif

                                                @if($item['schedule']->location)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        {{ $item['schedule']->location }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-8 text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-sm">No classes scheduled</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl p-8 sm:p-12 text-center">
                        <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No classes scheduled</h3>
                        <p class="text-gray-600">Your children are not enrolled in any classes yet.</p>
                    </div>
                @endif
            @else
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No children linked</h3>
                    <p class="text-gray-600 mb-6">Register your child to view their class schedules.</p>
                    <a href="{{ route('parent.register.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Register Child
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

