<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.invoices.show', $invoice) }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Invoice</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $invoice->invoice_number }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('owner.invoices.update', $invoice) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Member Info (Read-only) -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Member</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">{{ substr($invoice->member->name ?? 'N', 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $invoice->member->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $invoice->member->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Invoice Type (Read-only) -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-1">Invoice Type</p>
                    <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $invoice->type)) }}</p>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount (RM) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $invoice->amount) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('amount') border-red-500 @enderror" placeholder="0.00">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Due Date
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date', $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes / Description
                    </label>
                    <textarea name="notes" id="notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('notes') border-red-500 @enderror" placeholder="Additional notes or description...">{{ old('notes', $invoice->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold">Note:</p>
                            <p class="mt-1">Only amount, due date, and notes can be edited for pending invoices. Member and invoice type cannot be changed.</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('owner.invoices.show', $invoice) }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                        Update Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>

