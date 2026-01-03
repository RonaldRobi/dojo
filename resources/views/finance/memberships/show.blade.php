<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Membership Details</h2>
                <p class="text-sm text-gray-600 mt-1">View membership information</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('finance.memberships.edit', $membership) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('finance.memberships.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Member</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $membership->member->name ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Type</dt>
                <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $membership->type }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $membership->start_date->format('M d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $membership->end_date->format('M d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Price</dt>
                <dd class="mt-1 text-lg font-bold text-gray-900">RM {{ number_format($membership->price, 2) }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    @php
                        $statusColor = $membership->status === 'active' ? 'bg-green-100 text-green-800' : ($membership->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800');
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                        {{ ucfirst($membership->status) }}
                    </span>
                </dd>
            </div>
        </dl>
    </div>
</x-app-layout>

