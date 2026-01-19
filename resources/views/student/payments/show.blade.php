<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Invoice Details
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $invoice->invoice_number }}</p>
            </div>
            <a href="{{ route('student.payments.index') }}" 
               class="hidden sm:inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                <!-- Status Banner -->
                <div class="px-4 sm:px-6 py-4 {{ $invoice->status === 'paid' ? 'bg-green-500' : ($invoice->status === 'pending' ? 'bg-yellow-500' : 'bg-red-500') }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            @if($invoice->status === 'paid')
                                <svg class="h-6 w-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="h-6 w-6 text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                            <span class="text-white font-bold text-lg">{{ ucfirst($invoice->status) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="p-4 sm:p-6 space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Invoice Number</label>
                            <p class="text-base font-bold text-gray-900">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Invoice Date</label>
                            <p class="text-base font-bold text-gray-900">{{ $invoice->invoice_date ? $invoice->invoice_date->format('F d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Description</label>
                            <p class="text-base font-bold text-gray-900">{{ $invoice->description ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Payment Method</label>
                            <p class="text-base font-bold text-gray-900">{{ $invoice->payment_method ? ucfirst($invoice->payment_method) : 'N/A' }}</p>
                        </div>
                    </div>

                    <!-- Amount Details -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Subtotal:</span>
                            <span class="text-base font-semibold text-gray-900">RM {{ number_format($invoice->total_amount, 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <span class="text-lg font-bold text-gray-900">Total Amount:</span>
                            <span class="text-2xl font-bold text-blue-600">RM {{ number_format($invoice->total_amount, 0) }}</span>
                        </div>
                    </div>

                    @if($invoice->payment_reference)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Payment Reference</label>
                            <p class="text-sm font-mono text-gray-900">{{ $invoice->payment_reference }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Back Button (Mobile) -->
            <div class="mt-6 sm:hidden">
                <a href="{{ route('student.payments.index') }}" 
                   class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Payments
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

