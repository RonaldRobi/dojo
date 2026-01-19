<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('owner.payments.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Payment Details</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $payment->payment_reference }}</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $payment->payment_reference }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Payment Date: {{ \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        @if($payment->status === 'completed')
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">Completed</span>
                        @elseif($payment->status === 'pending')
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </div>
                </div>

                <!-- Member & Invoice Info -->
                <div class="border-t border-gray-200 pt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Member</h4>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                <span class="text-purple-600 font-semibold">{{ substr($payment->invoice->member->name ?? 'N', 0, 2) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $payment->invoice->member->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->invoice->member->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-500 uppercase mb-3">Invoice</h4>
                        <p class="font-semibold text-gray-900">{{ $payment->invoice->invoice_number ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $payment->invoice->invoice_type ?? 'N/A')) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500">Amount Paid</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">RM {{ number_format($payment->amount, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Payment Method</p>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                    </div>
                    @if($payment->notes)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-500">Notes</p>
                        <p class="text-gray-900 mt-1">{{ $payment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Quick Information</h4>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-500">Payment Ref</p>
                        <p class="font-semibold text-gray-900 text-sm">{{ $payment->payment_reference }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($payment->status) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Amount</p>
                        <p class="font-semibold text-gray-900">RM {{ number_format($payment->amount, 0) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Method</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Paid At</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($payment->paid_at ?? $payment->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Related Invoice -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Related Invoice</h4>
                <div class="space-y-2">
                    <p class="text-sm text-gray-900"><span class="text-gray-500">Invoice:</span> {{ $payment->invoice->invoice_number }}</p>
                    <p class="text-sm text-gray-900"><span class="text-gray-500">Type:</span> {{ ucfirst(str_replace('_', ' ', $payment->invoice->invoice_type)) }}</p>
                    <p class="text-sm text-gray-900"><span class="text-gray-500">Status:</span> {{ ucfirst($payment->invoice->payment_status) }}</p>
                </div>
                <a href="{{ route('owner.invoices.show', $payment->invoice) }}" class="block w-full mt-4 px-4 py-2 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition-colors text-sm">
                    View Invoice
                </a>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('owner.payments.index') }}" class="block w-full px-4 py-2 bg-gray-200 text-gray-700 text-center rounded-lg hover:bg-gray-300 transition-colors">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

