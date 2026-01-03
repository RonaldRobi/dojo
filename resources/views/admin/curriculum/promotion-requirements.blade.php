<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Promotion Requirements</h2>
            <p class="text-sm text-gray-500 mt-1">Manage promotion requirements for each rank</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-white flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Requirements</h3>
            <button onclick="document.getElementById('requirementModal').classList.remove('hidden')" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-lg shadow-md hover:from-purple-700 hover:to-purple-800 hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Requirement
            </button>
        </div>
        <!-- Filters -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex gap-4">
                <select name="dojo_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Dojos</option>
                    @foreach($dojos as $dojo)
                        <option value="{{ $dojo->id }}" {{ request('dojo_id') == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                    @endforeach
                </select>
                <select name="rank_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Ranks</option>
                    @foreach($ranks as $rank)
                        <option value="{{ $rank->id }}" {{ request('rank_id') == $rank->id ? 'selected' : '' }}>{{ $rank->name }} ({{ $rank->dojo->name ?? 'Global' }})</option>
                    @endforeach
                </select>
                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('admin.curriculum.promotion-requirements') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Reset</a>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Value</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requirements as $requirement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $requirement->rank->name ?? '-' }} ({{ $requirement->rank->dojo->name ?? 'Global' }})</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $requirement->requirement_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $requirement->requirement_value }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($requirement->description ?? '-', 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick="editRequirement({{ json_encode($requirement) }})" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                <form action="{{ route('admin.curriculum.requirements.destroy', $requirement) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No data available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $requirements->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div id="requirementModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold" id="modalTitle">Add Requirement</h3>
                <button onclick="document.getElementById('requirementModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="requirementForm" method="POST" action="{{ route('admin.curriculum.requirements.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="requirement_id" id="requirementId">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rank *</label>
                    <select name="rank_id" id="requirementRank" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Select Rank</option>
                        @foreach($ranks as $rank)
                            <option value="{{ $rank->id }}">{{ $rank->name }} ({{ $rank->dojo->name ?? 'Global' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Requirement Type *</label>
                    <select name="requirement_type" id="requirementType" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="">Select Type</option>
                        <option value="attendance_min">Minimum Attendance</option>
                        <option value="exam_required">Exam Required</option>
                        <option value="recommendation_required">Recommendation Required</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Value *</label>
                    <input type="text" name="requirement_value" id="requirementValue" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="e.g. 80 or Yes">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="requirementDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Save</button>
                    <button type="button" onclick="document.getElementById('requirementModal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editRequirement(requirement) {
            document.getElementById('modalTitle').textContent = 'Edit Requirement';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('requirementForm').action = `/admin/curriculum/requirements/${requirement.id}`;
            document.getElementById('requirementId').value = requirement.id;
            document.getElementById('requirementRank').value = requirement.rank_id || '';
            document.getElementById('requirementType').value = requirement.requirement_type || '';
            document.getElementById('requirementValue').value = requirement.requirement_value || '';
            document.getElementById('requirementDescription').value = requirement.description || '';
            document.getElementById('requirementModal').classList.remove('hidden');
        }

        document.getElementById('requirementForm').addEventListener('submit', function(e) {
            if (document.getElementById('formMethod').value === 'PUT') {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);
                formData.append('_method', 'PUT');
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(() => window.location.reload());
            }
        });
    </script>
</x-app-layout>

