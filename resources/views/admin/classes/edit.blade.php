<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.classes.templates') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Edit Class
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Update class information</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.classes.update', $class->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Branch/Dojo Selection -->
                <div>
                    <label for="dojo_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Branch/Dojo <span class="text-red-500">*</span>
                    </label>
                    <select name="dojo_id" id="dojo_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('dojo_id') border-red-500 @enderror">
                        <option value="">Select Branch</option>
                        @foreach($dojos as $dojo)
                            <option value="{{ $dojo->id }}" {{ old('dojo_id', $class->dojo_id) == $dojo->id ? 'selected' : '' }}>
                                {{ $dojo->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('dojo_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Class Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Class Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $class->name) }}" required
                        placeholder="e.g., Advanced Sparring, Beginner Basics, Kids Class"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4" 
                        placeholder="Describe the class level, requirements, or any special notes..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('description') border-red-500 @enderror">{{ old('description', $class->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Max Capacity -->
                <div>
                    <label for="max_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                        Max Capacity (Students)
                    </label>
                    <input type="number" name="max_capacity" id="max_capacity" value="{{ old('max_capacity', $class->max_capacity) }}" min="1"
                        placeholder="e.g., 30"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('max_capacity') border-red-500 @enderror">
                    @error('max_capacity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $class->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">
                            Active Class <span class="text-gray-500">(Students can enroll)</span>
                        </span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.classes.templates') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                        Update Class
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

