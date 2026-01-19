<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.payments.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Record Payment</h2>
                <p class="text-sm text-gray-600 mt-1">Record a new payment for pending invoice</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="{{ route('owner.payments.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            @csrf

            <div class="space-y-6">
                <!-- Invoice Selection -->
                <div>
                    <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Pending Invoice <span class="text-red-500">*</span>
                    </label>
                    <select name="invoice_id" id="invoice_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('invoice_id') border-red-500 @enderror" onchange="updateAmount(this)">
                        <option value="">-- Select Invoice --</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" 
                                    data-amount="{{ $invoice->amount }}" 
                                    data-member="{{ $invoice->member->name }}"
                                    data-type="{{ ucfirst(str_replace('_', ' ', $invoice->invoice_type)) }}"
                                    {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                {{ $invoice->invoice_number }} - {{ $invoice->member->name }} - RM {{ number_format($invoice->amount, 0) }}
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Details Preview -->
                <div id="invoice-preview" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Invoice Details</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Member</p>
                            <p id="preview-member" class="font-semibold text-gray-900">-</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Invoice Type</p>
                            <p id="preview-type" class="font-semibold text-gray-900">-</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-gray-500">Amount</p>
                            <p id="preview-amount" class="text-xl font-bold text-purple-600">RM 0</p>
                        </div>
                    </div>
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Amount (RM) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('amount') border-red-500 @enderror" placeholder="0.00">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Enter the amount being paid. Can be partial payment.</p>
                </div>

                <!-- Payment Method -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method" id="payment_method" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('payment_method') border-red-500 @enderror">
                        <option value="">-- Select Method --</option>
                        <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="online" {{ old('payment_method') === 'online' ? 'selected' : '' }}>Online Banking</option>
                        <option value="fpx" {{ old('payment_method') === 'fpx' ? 'selected' : '' }}>FPX</option>
                        <option value="bayar_cash" {{ old('payment_method') === 'bayar_cash' ? 'selected' : '' }}>Bayar.cash</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Reference -->
                <div>
                    <label for="payment_reference" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Reference / Transaction ID
                    </label>
                    <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('payment_reference') border-red-500 @enderror" placeholder="e.g., TXN123456">
                    @error('payment_reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Optional: Bank reference number or transaction ID</p>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('notes') border-red-500 @enderror" placeholder="Additional notes...">{{ old('notes') }}</textarea>
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
                            <p class="font-semibold">Important:</p>
                            <ul class="mt-1 list-disc list-inside space-y-1">
                                <li>Recording this payment will update the invoice status to "Paid"</li>
                                <li>Make sure to verify the payment before recording</li>
                                <li>You can enter partial payments if needed</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('owner.payments.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg font-semibold hover:from-purple-700 hover:to-purple-800 transition-all shadow-md hover:shadow-lg">
                        Record Payment
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateAmount(select) {
            const selectedOption = select.options[select.selectedIndex];
            const amount = selectedOption.getAttribute('data-amount');
            const member = selectedOption.getAttribute('data-member');
            const type = selectedOption.getAttribute('data-type');
            
            if (amount) {
                document.getElementById('amount').value = amount;
                document.getElementById('preview-amount').textContent = 'RM ' + parseFloat(amount).toLocaleString('en-MY', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                document.getElementById('preview-member').textContent = member;
                document.getElementById('preview-type').textContent = type;
                document.getElementById('invoice-preview').classList.remove('hidden');
            } else {
                document.getElementById('amount').value = '';
                document.getElementById('invoice-preview').classList.add('hidden');
            }
        }
    </script>
</x-app-layout>

