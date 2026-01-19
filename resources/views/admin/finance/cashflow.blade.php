<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Cashflow Report</h2>
            <p class="text-sm text-gray-500 mt-1">View cashflow analysis</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Cashflow</h3>
            <div class="text-2xl font-bold text-green-600">RM {{ number_format($totalCashflow, 0) }}</div>
        </div>
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex gap-4">
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <input type="date" name="date_to" value="{{ $dateTo }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
            </form>
        </div>
        <div class="p-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">By Payment Method</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Payment Method</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Count</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($byMethod as $method)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ ucfirst($method->payment_method ?? '-') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">RM {{ number_format($method->total, 0) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $method->count }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No data available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

