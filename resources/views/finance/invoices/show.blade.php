<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Invoice #{{ $invoice->invoice_number }}</h2>
                <p class="text-sm text-gray-600 mt-1">Invoice Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('finance.invoices.edit', $invoice) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('finance.invoices.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Information</h3>
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                        <dd class="mt-1 text-sm text-gray-900">#{{ $invoice->invoice_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $invoice->member->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $invoice->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'overdue' => 'bg-red-100 text-red-800',
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount</dt>
                        <dd class="mt-1 text-lg font-bold text-gray-900">RM {{ number_format($invoice->total_amount, 0) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            @if($invoice->items->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Invoice Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($invoice->items as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900">{{ $item->description }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500">RM {{ number_format($item->unit_price, 0) }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">RM {{ number_format($item->total_price, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            @if($invoice->payments->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payments</h3>
                    <div class="space-y-3">
                        @foreach($invoice->payments as $payment)
                            <div class="p-3 bg-green-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900">RM {{ number_format($payment->amount, 0) }}</span>
                                    <span class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y') }}</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-1">{{ $payment->payment_method }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

