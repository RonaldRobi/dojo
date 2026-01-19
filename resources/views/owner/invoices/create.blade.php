<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.invoices.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Create Invoice</h2>
                <p class="text-sm text-gray-600 mt-1">Generate a new invoice for member</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('owner.invoices.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf

            <div class="space-y-6">
                <!-- Member Selection -->
                <div>
                    <label for="member_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Member <span class="text-red-500">*</span>
                    </label>
                    <select name="member_id" id="member_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('member_id') border-red-500 @enderror">
                        <option value="">-- Select Member --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->user->email ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('member_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Invoice Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('type') border-red-500 @enderror">
                        <option value="">-- Select Type --</option>
                        <option value="membership" {{ old('type') === 'membership' ? 'selected' : '' }}>Membership Fee</option>
                        <option value="class" {{ old('type') === 'class' ? 'selected' : '' }}>Class Fee</option>
                        <option value="event" {{ old('type') === 'event' ? 'selected' : '' }}>Event Fee</option>
                        <option value="private" {{ old('type') === 'private' ? 'selected' : '' }}>Private Lesson</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount (RM) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('amount') border-red-500 @enderror" placeholder="0.00">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Due Date
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Default: 7 days from today</p>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes / Description
                    </label>
                    <textarea name="notes" id="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('notes') border-red-500 @enderror" placeholder="Additional notes or description...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('owner.invoices.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                        Create Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

