<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Retention Report</h2>
            <p class="text-sm text-gray-600 mt-1">Member retention analytics</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        @if(isset($dojo))
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">{{ $dojo->name }}</h3>
                <dl class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-purple-600">Total Members</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $data['total_members'] ?? 0 }}</dd>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-green-600">Active Members</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $data['active_members'] ?? 0 }}</dd>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-blue-600">Retention Rate</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($data['retention_rate'] ?? 0, 1) }}%</dd>
                    </div>
                </dl>
            </div>
        @elseif(isset($data) && count($data) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dojo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Members</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active Members</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retention Rate</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['dojo']->name ?? $item['dojo'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['total_members'] ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['active_members'] ?? 0 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format($item['retention_rate'] ?? 0, 1) }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 py-8">No retention data available</p>
        @endif
    </div>
</x-app-layout>

