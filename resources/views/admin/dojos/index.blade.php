<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Dojos / Branches</h2>
            <p class="text-sm text-gray-600 mt-1">Manage all branches and assign owners</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Branches</h3>
            <a href="{{ route('admin.dojos.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-lg shadow-md hover:from-purple-700 hover:to-purple-800 hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add New Dojo
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($dojos as $dojo)
                        @php
                            $owner = $dojo->users->first();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $dojo->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dojo->address ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($owner)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                                            <span class="text-purple-600 font-semibold text-xs">{{ substr($owner->name, 0, 2) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $owner->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $owner->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        No Owner
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dojo->members_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dojo->users_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dojo->classes_count ?? 0 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    @if(!$owner)
                                        <a href="{{ route('admin.dojos.assign-owner', $dojo) }}" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors" title="Assign Owner">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                            Add Owner
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.dojos.show', $dojo) }}" class="text-purple-600 hover:text-purple-900">View</a>
                                    <a href="{{ route('admin.dojos.edit', $dojo) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form action="{{ route('admin.dojos.destroy', $dojo) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No dojos found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $dojos->links() }}
        </div>
    </div>
</x-app-layout>
