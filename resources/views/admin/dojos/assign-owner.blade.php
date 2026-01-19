<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.dojos.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Assign Owner to {{ $dojo->name }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Select a user to be the owner of this branch</p>
            </div>
        </div>
    </x-slot>

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                <h3 class="text-lg font-semibold">Dojo Information</h3>
            </div>
            
            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Dojo Name</p>
                        <p class="font-semibold text-gray-900">{{ $dojo->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="font-semibold text-gray-900">{{ $dojo->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.dojos.assign-owner.store', $dojo) }}" class="p-6">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Select User to Assign as Owner <span class="text-red-500">*</span>
                        </label>
                        
                        @if($availableUsers->count() > 0)
                            <select name="user_id" id="user_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('user_id') border-red-500 @enderror">
                                <option value="">-- Select User --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">No Available Users</p>
                                        <p class="text-sm text-yellow-700 mt-1">All users have already been assigned as owners or there are no active users in the system.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold">About Owner Role</p>
                                <ul class="mt-2 space-y-1 list-disc list-inside">
                                    <li>Owner has full control over this dojo/branch</li>
                                    <li>Owner can manage members, classes, and instructors</li>
                                    <li>Each dojo can have one or more owners</li>
                                    <li>Owner role is specific to this dojo only</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.dojos.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                            Cancel
                        </a>
                        @if($availableUsers->count() > 0)
                            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                    </svg>
                                    Assign Owner
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

