<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">All Classes</h2>
                <p class="text-sm text-gray-500 mt-1">Manage classes across all branches</p>
            </div>
            <a href="{{ route('admin.classes.create') }}" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-semibold hover:from-blue-700 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Class
                </span>
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Classes</h3>
        </div>
        <!-- Filters -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search classes..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <select name="dojo_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Dojos</option>
                    @foreach($dojos as $dojo)
                        <option value="{{ $dojo->id }}" {{ request('dojo_id') == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('admin.classes.templates') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Branch</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($classes as $class)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                @if($class->description)
                                    <div class="text-xs text-gray-500">{{ Str::limit($class->description, 40) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $class->dojo->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $class->max_capacity ? $class->max_capacity . ' students' : 'No limit' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $class->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $class->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.classes.edit', $class->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this class?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <p class="mt-4 text-sm text-gray-500 font-medium">No classes found</p>
                            <p class="mt-1 text-xs text-gray-400">Try adjusting your filters or create a new class</p>
                            <a href="{{ route('admin.classes.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                Create Class
                            </a>
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">{{ $classes->links() }}</div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('success') }}
        </div>
    @endif
</x-app-layout>

