<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900">
                    Finance Dashboard
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage invoices, payments, and revenue</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Today Revenue -->
                <div class="group relative bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-green-100 text-sm font-medium uppercase tracking-wide">Today Revenue</p>
                                <p class="mt-2 text-3xl font-bold text-white">RM {{ number_format($stats['today_revenue'], 0) }}</p>
                                <p class="mt-1 text-xs text-green-100">From verified payments</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="group relative bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm font-medium uppercase tracking-wide">Monthly Revenue</p>
                                <p class="mt-2 text-3xl font-bold text-white">RM {{ number_format($stats['monthly_revenue']['total_revenue'] ?? 0, 0) }}</p>
                                <p class="mt-1 text-xs text-blue-100">{{ now()->format('F Y') }}</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:rotate-12 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="group relative bg-gradient-to-br from-yellow-500 to-amber-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-medium uppercase tracking-wide">Pending Payments</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['pending_payments'] }}</p>
                                <p class="mt-1 text-xs text-yellow-100">Awaiting verification</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overdue Invoices -->
                <div class="group relative bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                    <div class="relative p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-red-100 text-sm font-medium uppercase tracking-wide">Overdue Invoices</p>
                                <p class="mt-2 text-4xl font-bold text-white">{{ $stats['overdue_invoices'] }}</p>
                                <p class="mt-1 text-xs text-red-100">Requires attention</p>
                            </div>
                            <div class="p-4 bg-white bg-opacity-20 rounded-xl backdrop-blur-sm transform group-hover:scale-110 transition-transform duration-300">
                                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Payments -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-700 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Recent Payments</h3>
                                    <p class="text-sm text-green-100">Latest verified payments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentPayments->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentPayments as $payment)
                                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl border-l-4 border-green-500 hover:bg-green-100 transition-colors duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-green-500 rounded-lg">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $payment->invoice->member->name ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-600">{{ $payment->payment_date->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-green-600">RM {{ number_format($payment->amount, 0) }}</p>
                                            <p class="text-xs text-gray-500">{{ $payment->payment_method }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>No recent payments</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Recent Invoices</h3>
                                    <p class="text-sm text-blue-100">Latest generated invoices</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentInvoices->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentInvoices as $invoice)
                                    @php
                                        $borderColor = $invoice->status === 'paid' ? 'green' : ($invoice->status === 'overdue' ? 'red' : 'yellow');
                                        $bgColor = $invoice->status === 'paid' ? 'green' : ($invoice->status === 'overdue' ? 'red' : 'yellow');
                                    @endphp
                                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl border-l-4 @if($borderColor === 'green') border-green-500 @elseif($borderColor === 'red') border-red-500 @else border-yellow-500 @endif hover:bg-blue-100 transition-colors duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="p-3 bg-blue-500 rounded-lg">
                                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $invoice->member->name ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-600">#{{ $invoice->invoice_number }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-blue-600">RM {{ number_format($invoice->total_amount, 0) }}</p>
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full @if($bgColor === 'green') bg-green-100 text-green-800 @elseif($bgColor === 'red') bg-red-100 text-red-800 @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p>No recent invoices</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

