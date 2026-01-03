<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Audit Log Details</h2>
                <p class="text-sm text-gray-600 mt-1">View audit log information</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <dl class="grid grid-cols-1 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">User</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->user->name ?? 'System' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Action</dt>
                <dd class="mt-1">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 capitalize">
                        {{ $auditLog->action }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Model</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->model }} #{{ $auditLog->model_id }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Dojo</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->dojo->name ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->ip_address }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $auditLog->created_at->format('M d, Y H:i:s') }}</dd>
            </div>
            @if($auditLog->changes)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Changes</dt>
                    <dd class="mt-1">
                        <pre class="p-4 bg-gray-50 rounded-lg text-xs overflow-auto">{{ json_encode($auditLog->changes, JSON_PRETTY_PRINT) }}</pre>
                    </dd>
                </div>
            @endif
        </dl>
    </div>
</x-app-layout>

