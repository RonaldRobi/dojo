<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $dojo->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Dojo Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.dojos.edit', $dojo) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('admin.dojos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $dojo->name }}</dd>
                </div>
                @if($dojo->email)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dojo->email }}</dd>
                    </div>
                @endif
                @if($dojo->phone)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dojo->phone }}</dd>
                    </div>
                @endif
                @if($dojo->website_url)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <a href="{{ $dojo->website_url }}" target="_blank" class="text-purple-600 hover:text-purple-900">{{ $dojo->website_url }}</a>
                        </dd>
                    </div>
                @endif
                @if($dojo->address)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dojo->address }}</dd>
                    </div>
                @endif
                @if($dojo->description)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $dojo->description }}</dd>
                    </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
            <dl class="space-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Members</dt>
                    <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $dojo->members_count ?? 0 }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Users</dt>
                    <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $dojo->users_count ?? 0 }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Total Classes</dt>
                    <dd class="mt-1 text-2xl font-bold text-gray-900">{{ $dojo->classes_count ?? 0 }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-app-layout>

