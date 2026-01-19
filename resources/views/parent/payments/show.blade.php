<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Invoice Details
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $invoice->invoice_number }}</p>
            </div>
            @if(!in_array($invoice->status, ['cancelled', 'failed']))
                <a href="{{ route('parent.payments.index') }}" 
                   class="hidden sm:inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Payments
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <!-- Invoice Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold">{{ $invoice->invoice_number }}</h3>
                            <p class="text-blue-100 mt-1">Invoice for {{ $invoice->member->name ?? 'Member' }}</p>
                        </div>
                        <div class="text-right">
                            @if($invoice->status === 'paid')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-500 text-white shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Paid
                                </span>
                            @elseif($invoice->status === 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-500 text-white shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-500 text-white shadow-lg">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div class="px-8 py-6 space-y-6">
                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Invoice Information</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Invoice Date:</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Due Date:</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Type:</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ ucfirst($invoice->type) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Payment Information</h4>
                            <dl class="space-y-2">
                                @if($invoice->status === 'paid')
                                    <div class="flex justify-between">
                                        <dt class="text-sm text-gray-600">Paid At:</dt>
                                        <dd class="text-sm font-semibold text-gray-900">{{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, h:i A') : '-' }}</dd>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Payment Gateway:</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $invoice->payment_gateway ? ucfirst($invoice->payment_gateway) : '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Reference:</dt>
                                    <dd class="text-sm font-semibold text-gray-900">{{ $invoice->payment_reference ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($invoice->description)
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase mb-2">Description</h4>
                            <p class="text-sm text-gray-700">{{ $invoice->description }}</p>
                        </div>
                    @endif

                    <!-- Amount Breakdown -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-4">Amount Breakdown</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Subtotal:</span>
                                <span class="text-sm font-semibold text-gray-900">RM {{ number_format($invoice->amount, 0) }}</span>
                            </div>
                            @if($invoice->discount_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Discount:</span>
                                    <span class="text-sm font-semibold text-red-600">- RM {{ number_format($invoice->discount_amount, 0) }}</span>
                                </div>
                            @endif
                            @if($invoice->tax_amount > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Tax:</span>
                                    <span class="text-sm font-semibold text-gray-900">RM {{ number_format($invoice->tax_amount, 0) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between pt-3 border-t border-gray-200">
                                <span class="text-base font-bold text-gray-900">Total Amount:</span>
                                <span class="text-xl font-bold text-blue-600">RM {{ number_format($invoice->total_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($invoice->status === 'pending')
                        <div class="border-t border-gray-200 pt-6">
                            <form action="{{ route('parent.payment.create', $invoice->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="payment_channel" value="1">
                                <button type="submit" class="inline-flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Pay Now via FPX
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Back Button for Cancelled/Failed Status (Bottom) -->
                    @if(in_array($invoice->status, ['cancelled', 'failed']))
                        <div class="border-t border-gray-200 pt-6">
                            <a href="{{ route('parent.payments.index') }}" 
                               class="inline-flex items-center justify-center w-full px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Payments
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
