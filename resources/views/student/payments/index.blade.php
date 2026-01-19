<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900">
                    Payment History
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">View all your payment transactions</p>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8 pb-24 lg:pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($invoices && $invoices->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden lg:block bg-white rounded-xl shadow-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-blue-600 to-indigo-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Invoice #</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Description</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoices as $invoice)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $invoice->invoice_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->description ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        RM {{ number_format($invoice->total_amount, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($invoice->status === 'paid')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                        @elseif($invoice->status === 'pending')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ ucfirst($invoice->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('student.payments.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="lg:hidden space-y-4">
                    @foreach($invoices as $invoice)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-4 flex items-center justify-between">
                                <h3 class="text-base font-bold text-white">{{ $invoice->invoice_number }}</h3>
                                @if($invoice->status === 'paid')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-500 text-white">Paid</span>
                                @elseif($invoice->status === 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-500 text-white">Pending</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white">{{ ucfirst($invoice->status) }}</span>
                                @endif
                            </div>
                            <div class="p-4 space-y-3 text-sm">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-700">Description:</span>
                                    <span class="ml-auto text-gray-900">{{ $invoice->description ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium text-gray-700">Date:</span>
                                    <span class="ml-auto text-gray-900">{{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : 'N/A' }}</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3 mt-3 flex items-center">
                                    <span class="text-base font-bold text-gray-900">Total:</span>
                                    <span class="ml-auto text-xl font-bold text-blue-600">RM {{ number_format($invoice->total_amount, 0) }}</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 flex justify-end">
                                <a href="{{ route('student.payments.show', $invoice->id) }}" class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800">View Details</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $invoices->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-xl overflow-hidden">
                    <div class="p-8 sm:p-12 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-blue-100 mb-4">
                            <svg class="h-10 w-10 sm:h-12 sm:w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">No Payment History</h3>
                        <p class="text-sm sm:text-base text-gray-600">You don't have any payment transactions yet.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

