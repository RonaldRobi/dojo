<x-app-layout title="Member Details">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('owner.members.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Member Details') }}
                </h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('owner.members.edit', $member) }}" class="btn btn-outline">
                    Edit
                </a>
                <form action="{{ route('owner.members.destroy', $member) }}" method="POST" 
                      onsubmit="return confirmDelete('Are you sure you want to delete this member?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Info Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                        </div>
                        <div class="card-body">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        <span class="badge {{ $member->status === 'active' ? 'badge-success' : ($member->status === 'leave' ? 'badge-warning' : 'badge-danger') }}">
                                            {{ ucfirst($member->status) }}
                                        </span>
                                    </dd>
                                </div>
                                @if($member->birth_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->birth_date->format('F d, Y') }}</dd>
                                </div>
                                @endif
                                @if($member->gender)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($member->gender) }}</dd>
                                </div>
                                @endif
                                @if($member->phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->phone }}</dd>
                                </div>
                                @endif
                                @if($member->join_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Join Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->join_date->format('F d, Y') }}</dd>
                                </div>
                                @endif
                                @if($member->currentBelt)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Current Belt</dt>
                                    <dd class="mt-1">
                                        <span class="badge badge-info">{{ $member->currentBelt->name }}</span>
                                    </dd>
                                </div>
                                @endif
                                @if($member->address)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->address }}</dd>
                                </div>
                                @endif
                                @if($member->medical_notes)
                                <div class="md:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Medical Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $member->medical_notes }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Attendance Card -->
                    <div class="card">
                        <div class="card-header flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Attendance</h3>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-4 text-gray-500">
                                Attendance records will be displayed here
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- QR Code Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">QR Code</h3>
                        </div>
                        <div class="card-body text-center">
                            @if($member->qr_code)
                                <div class="bg-gray-100 p-4 rounded-lg inline-block mb-4">
                                    <div class="w-48 h-48 bg-white flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">QR Code Placeholder</span>
                                    </div>
                                </div>
                                <button onclick="regenerateQR()" class="btn btn-outline text-sm">
                                    Regenerate QR Code
                                </button>
                            @else
                                <p class="text-gray-500 mb-4">No QR code generated</p>
                                <button onclick="regenerateQR()" class="btn btn-primary text-sm">
                                    Generate QR Code
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="card-body space-y-2">
                            <a href="#" class="block btn btn-outline text-center">Mark Attendance</a>
                            <a href="#" class="block btn btn-outline text-center">Enroll in Class</a>
                            <a href="#" class="block btn btn-outline text-center">View Progress</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function regenerateQR() {
            fetch('{{ route('owner.members.regenerate-qr', $member) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                alert('QR code regenerated successfully');
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error regenerating QR code');
            });
        }
    </script>
    @endpush
</x-app-layout>

