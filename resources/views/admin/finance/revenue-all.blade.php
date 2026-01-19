<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Revenue Report</h2>
            <p class="text-sm text-gray-500 mt-1">View revenue across all branches</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Revenue by Branch</h3>
            <div class="text-2xl font-bold text-green-600">RM {{ number_format($totalRevenue, 0) }}</div>
        </div>
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex gap-4">
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <input type="date" name="date_to" value="{{ $dateTo }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Members</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Invoices</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($revenues as $revenue)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $revenue['name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">RM {{ number_format($revenue['total_revenue'], 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($revenue['members_count']) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($revenue['invoices_count']) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

