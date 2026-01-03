<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Member Status</h2>
            <p class="text-sm text-gray-500 mt-1">View member status across all branches</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Members by Status</h3>
        </div>
        <!-- Stats -->
        <div class="p-6 border-b border-gray-200 bg-gray-50 grid grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Active</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Inactive</p>
                <p class="text-2xl font-bold text-gray-600">{{ $stats['inactive'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">On Leave</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['leave'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-4 rounded-lg border border-gray-200">
                <p class="text-xs text-gray-500 mb-1">Total</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] ?? 0 }}</p>
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
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>On Leave</option>
                </select>
                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('admin.members.status') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Member</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dojo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($members as $member)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $member->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $member->dojo->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $member->status === 'active' ? 'bg-green-100 text-green-800' : ($member->status === 'leave' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">{{ $members->links() }}</div>
    </div>
</x-app-layout>

