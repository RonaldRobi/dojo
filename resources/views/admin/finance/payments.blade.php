<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Invoices & Payments</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor all invoices and payments across branches</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Invoice Records</h3>
        </div>
        
        <!-- Stats Grid -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Invoices -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Total Invoices</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_invoices'] ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">RM {{ number_format($stats['total_amount'] ?? 0, 0) }}</p>
                </div>

                <!-- Paid -->
                <div class="bg-white p-4 rounded-lg border border-green-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Paid</p>
                            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['paid_count'] ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">RM {{ number_format($stats['paid_amount'] ?? 0, 0) }}</p>
                </div>

                <!-- Pending -->
                <div class="bg-white p-4 rounded-lg border border-yellow-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Pending</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending_count'] ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">RM {{ number_format($stats['pending_amount'] ?? 0, 0) }}</p>
                </div>

                <!-- Overdue -->
                <div class="bg-white p-4 rounded-lg border border-red-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Overdue</p>
                            <p class="text-2xl font-bold text-red-600">{{ number_format($stats['overdue_count'] ?? 0) }}</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">RM {{ number_format($stats['overdue_amount'] ?? 0, 0) }}</p>
                </div>
            </div>
        </div>
        <!-- Filters -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <select name="dojo_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="">All Dojos</option>
                    @foreach($dojos as $dojo)
                        <option value="{{ $dojo->id }}" {{ request('dojo_id') == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                    @endforeach
                </select>
                
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="">All Status</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <select name="invoice_type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                    <option value="">All Types</option>
                    <option value="registration" {{ request('invoice_type') == 'registration' ? 'selected' : '' }}>Registration</option>
                    <option value="monthly" {{ request('invoice_type') == 'monthly' ? 'selected' : '' }}>Monthly Fee</option>
                    <option value="event" {{ request('invoice_type') == 'event' ? 'selected' : '' }}>Event</option>
                    <option value="exam" {{ request('invoice_type') == 'exam' ? 'selected' : '' }}>Exam</option>
                </select>
                
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="From Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="To Date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-5 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all font-semibold text-sm shadow-md hover:shadow-lg">
                        Filter
                    </button>
                    <a href="{{ route('admin.finance.payments') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all text-sm font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Dojo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono font-semibold text-blue-600">{{ $invoice->invoice_number ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $invoice->member->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $invoice->member->member_number ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $invoice->dojo->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-lg bg-blue-50 text-blue-700">
                                    {{ ucfirst($invoice->invoice_type ?? '-') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                RM {{ number_format($invoice->total_amount, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'paid' => 'bg-green-100 text-green-800 border-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                        'overdue' => 'bg-red-100 text-red-800 border-red-200',
                                        'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
                                    ];
                                    $colorClass = $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full border {{ $colorClass }}">
                                    {{ ucfirst($invoice->status ?? '-') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="mt-4 text-sm text-gray-500 font-medium">No invoices found</p>
                                <p class="mt-1 text-xs text-gray-400">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $invoices->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>

