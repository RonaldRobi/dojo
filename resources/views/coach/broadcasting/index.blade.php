<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Broadcasting</h2>
                <p class="text-sm text-gray-600 mt-1">Send messages to your students</p>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('coach.broadcasting.store') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="Enter message title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                    <textarea name="message" rows="6" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" placeholder="Enter your message">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                    <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                        <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Students *</label>
                    <div class="border border-gray-300 rounded-lg p-4 max-h-96 overflow-y-auto">
                        <div class="mb-3">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                            <label for="select-all" class="ml-2 text-sm font-medium text-gray-700">Select All</label>
                        </div>
                        <div class="space-y-2">
                            @forelse($students as $student)
                                <div class="flex items-center">
                                    <input type="checkbox" name="member_ids[]" value="{{ $student->id }}" id="student-{{ $student->id }}" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 student-checkbox">
                                    <label for="student-{{ $student->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $student->name }}
                                        @if($student->currentBelt)
                                            <span class="text-xs text-gray-500">({{ $student->currentBelt->name }})</span>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No active students found in the dojo.</p>
                            @endforelse
                        </div>
                    </div>
                    @error('member_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Send Broadcast
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</x-app-layout>

