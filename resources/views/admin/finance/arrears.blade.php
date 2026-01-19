<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Arrears</h2>
            <p class="text-sm text-gray-500 mt-1">Manage pending and overdue invoices</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Overdue & Pending Invoices</h3>
        </div>
        <!-- Stats -->
        <div class="p-6 border-b border-gray-200 bg-gray-50 grid grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Pending Amount</p>
                <p class="text-xl font-bold text-yellow-600">RM {{ number_format($stats['total_pending'] ?? 0, 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['count_pending'] ?? 0 }} invoices</p>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Overdue Amount</p>
                <p class="text-xl font-bold text-red-600">RM {{ number_format($stats['total_overdue'] ?? 0, 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['count_overdue'] ?? 0 }} invoices</p>
            </div>
        </div>
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex gap-4">
                <select name="dojo_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Dojos</option>
                    @foreach($dojos as $dojo)
                        <option value="{{ $dojo->id }}" {{ request('dojo_id') == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('admin.finance.arrears') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $invoice->invoice_number ?? $invoice->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->member->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">RM {{ number_format($invoice->total_amount, 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">{{ $invoices->links() }}</div>
    </div>
</x-app-layout>

