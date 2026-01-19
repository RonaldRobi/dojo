<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Revenue Report</h2>
            <p class="text-sm text-gray-600 mt-1">{{ $dojo->name ?? 'Your Branch' }} - Financial overview</p>
        </div>
    </x-slot>

    <!-- Filters & Export -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                    <select name="period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" onchange="this.form.submit()">
                        <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>This Week</option>
                        <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>This Month</option>
                        <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>This Year</option>
                        <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Apply Filter
                    </button>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" name="export" value="csv" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Export CSV
                </button>
                <button type="submit" name="export" value="pdf" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Export PDF
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-purple-100 text-sm font-medium uppercase">Total Revenue</p>
            <p class="text-3xl font-bold mt-2">RM {{ number_format($totalRevenue, 0) }}</p>
            <p class="text-purple-100 text-xs mt-1">{{ $dateFrom }} - {{ $dateTo }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-blue-100 text-sm font-medium uppercase">Total Invoices</p>
            <p class="text-3xl font-bold mt-2">{{ number_format($totalInvoices) }}</p>
            <p class="text-blue-100 text-xs mt-1">Paid invoices</p>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <p class="text-green-100 text-sm font-medium uppercase">Average per Invoice</p>
            <p class="text-3xl font-bold mt-2">RM {{ number_format($averagePerInvoice, 0) }}</p>
            <p class="text-green-100 text-xs mt-1">Mean value</p>
        </div>
    </div>

    <!-- Revenue by Type -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Type</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($revenueByType as $type => $amount)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $type)) }}</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">RM {{ number_format($amount, 0) }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Invoice Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Invoice Details</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $invoice->member->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ ucfirst(str_replace('_', ' ', $invoice->type)) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                RM {{ number_format($invoice->amount, 0) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No invoices found for this period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

