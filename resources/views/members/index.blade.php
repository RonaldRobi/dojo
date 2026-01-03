<x-app-layout title="Members">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Members') }}
            </h2>
            <a href="{{ route('owner.members.create') }}" class="btn btn-primary">
                <svg class="h-4 w-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Member
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="card mb-6">
                <div class="card-body">
                    <form method="GET" action="{{ route('owner.members.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   placeholder="Name, email, or phone..." class="form-input">
                        </div>
                        <div class="w-48">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-input">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('owner.members.index') }}" class="btn btn-outline ml-2">Clear</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Members Table -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Current Belt</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="members-table-body">
                                <!-- Will be populated by JavaScript -->
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="px-6 py-4 border-t border-gray-200">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentPage = 1;

        function loadMembers(page = 1) {
            const params = new URLSearchParams(window.location.search);
            params.set('page', page);
            
            fetch(`{{ route('owner.members.index') }}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('members-table-body');
                
                if (data.data && data.data.length > 0) {
                    tbody.innerHTML = data.data.map(member => `
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-600 font-medium">${member.name.charAt(0).toUpperCase()}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${member.name}</div>
                                        ${member.user?.email ? `<div class="text-sm text-gray-500">${member.user.email}</div>` : ''}
                                    </div>
                                </div>
                            </td>
                            <td>${member.phone || '-'}</td>
                            <td>
                                ${member.current_belt 
                                    ? `<span class="badge badge-info">${member.current_belt.name}</span>` 
                                    : '<span class="text-gray-400">-</span>'}
                            </td>
                            <td>
                                <span class="badge ${member.status === 'active' ? 'badge-success' : member.status === 'leave' ? 'badge-warning' : 'badge-danger'}">
                                    ${member.status || 'N/A'}
                                </span>
                            </td>
                            <td>${member.join_date ? new Date(member.join_date).toLocaleDateString('id-ID') : '-'}</td>
                            <td>
                                <div class="flex space-x-2">
                                    <a href="/owner/members/${member.id}" class="text-blue-600 hover:text-blue-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="/owner/members/${member.id}/edit" class="text-yellow-600 hover:text-yellow-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-8 text-gray-500">No members found</td></tr>';
                }

                // Pagination
                if (data.links) {
                    renderPagination(data.links);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('members-table-body').innerHTML = 
                    '<tr><td colspan="6" class="text-center py-8 text-red-500">Error loading members</td></tr>';
            });
        }

        function renderPagination(links) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = links.map(link => `
                <a href="${link.url || '#'}" 
                   onclick="${link.url ? `event.preventDefault(); loadMembers(${link.label});` : 'return false;'}"
                   class="px-4 py-2 border rounded ${link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}">
                    ${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}
                </a>
            `).join('');
        }

        // Load members on page load
        loadMembers(1);
    </script>
    @endpush
</x-app-layout>

