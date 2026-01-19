<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-gray-900">
                {{ $member->name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">Detailed information and activities</p>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 xl:px-8 space-y-4 sm:space-y-6">
            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('parent.children.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
                <a href="{{ route('parent.children.progress', $member) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-chart-line mr-2"></i>
                    Progress & Grades
                </a>
            </div>

            <!-- Member Info Card -->
            <x-card>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->birth_date ? $member->birth_date->format('d M Y') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($member->gender ?? 'N/A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->phone ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->address ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dojo Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dojo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->dojo->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Current Belt</dt>
                                <dd class="mt-1">
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                                        {{ $member->currentBelt->name ?? 'White Belt' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                        {{ ucfirst($member->status ?? 'Active') }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Join Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->join_date ? $member->join_date->format('d M Y') : 'N/A' }}</dd>
                            </div>
                            @if($member->medical_notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Medical Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->medical_notes }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Recent Attendances -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Attendance History</h3>
                </x-slot>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $attendance->attendance_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $attendance->status == 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->classSchedule->dojoClass->name ?? 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No attendance history yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>

            <!-- Recent Invoices -->
            <x-card>
                <x-slot name="header">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Invoices</h3>
                        @if($totalDue > 0)
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg font-semibold">
                                Total: RM {{ number_format($totalDue, 0) }}
                            </span>
                        @endif
                    </div>
                </x-slot>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $invoice->invoice_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $invoice->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        RM {{ number_format($invoice->total_amount, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('parent.payments.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No invoices yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>

