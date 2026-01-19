<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900">
                My Children
            </h2>
            <p class="text-sm text-gray-600 mt-1">Manage and monitor your children's progress</p>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-xl text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mb-4 sm:mb-6 flex justify-end">
                <a href="{{ route('parent.register.create') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm sm:text-base font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="hidden sm:inline">Register New Child</span>
                    <span class="sm:hidden">Register</span>
                </a>
            </div>

            @if($children->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($children as $child)
                        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-xl sm:hover:shadow-2xl hover:-translate-y-1 sm:hover:-translate-y-2">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-4 sm:p-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-2xl font-bold text-white">{{ $child->name }}</h3>
                                        <p class="text-blue-100 text-sm mt-1">{{ $child->dojo->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="p-2 sm:p-3 bg-white bg-opacity-20 rounded-full backdrop-blur-sm">
                                        <i class="fas fa-user text-2xl sm:text-3xl text-white"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4 sm:p-6">
                                <div class="space-y-4">
                                    <!-- Current Belt -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Current Belt</span>
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                            {{ $child->currentBelt->name ?? 'White Belt' }}
                                        </span>
                                    </div>

                                    <!-- Enrolled Classes -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Enrolled Classes</span>
                                        <span class="text-lg font-bold text-gray-900">
                                            {{ $child->enrollments->where('status', 'active')->count() }}
                                        </span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="pt-3 sm:pt-4 border-t border-gray-200 space-y-2">
                                        <a href="{{ route('parent.children.show', $child) }}" 
                                            class="block w-full text-center px-3 sm:px-4 py-2 text-sm sm:text-base bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all transform hover:scale-105 font-semibold shadow-md">
                                            View Details
                                        </a>
                                        <a href="{{ route('parent.children.progress', $child) }}" 
                                            class="block w-full text-center px-3 sm:px-4 py-2 text-sm sm:text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all transform hover:scale-105 font-semibold shadow-md">
                                            <span class="hidden sm:inline">View Progress & Grades</span>
                                            <span class="sm:hidden">Progress</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                    <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-user-plus text-5xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No children registered</h3>
                    <p class="text-gray-600 mb-6">Register your child to start monitoring their progress</p>
                    <a href="{{ route('parent.register.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Register Child
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

