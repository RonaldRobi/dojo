<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Member Details') }} - {{ $member->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('owner.members.edit', $member) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('owner.members.index') }}" class="btn btn-outline">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                    </x-slot>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->phone ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->user->email ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Birth Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->birth_date ? $member->birth_date->format('M d, Y') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Gender</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($member->gender ?? 'N/A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($member->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($member->status == 'leave')
                                    <span class="badge badge-warning">Leave</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Join Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->join_date ? $member->join_date->format('M d, Y') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Style</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $member->style ?? 'N/A' }}</dd>
                        </div>
                        @if($member->address)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->address }}</dd>
                            </div>
                        @endif
                        @if($member->medical_notes)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Medical Notes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $member->medical_notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-card>

                <!-- Progress & Rank -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-gray-900">Current Rank</h3>
                    </x-slot>
                    @if($member->currentBelt)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="h-16 w-16 rounded-full flex items-center justify-center text-white font-bold text-xl" style="background-color: {{ $member->currentBelt->color ?? '#6B7280' }}">
                                    {{ substr($member->currentBelt->name, 0, 1) }}
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $member->currentBelt->name }}</h4>
                                <p class="text-sm text-gray-500">Level {{ $member->currentBelt->level }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No rank assigned yet</p>
                    @endif
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Photo -->
                <x-card>
                    <div class="text-center">
                        @if($member->profile_photo)
                            <img class="mx-auto h-32 w-32 rounded-full" src="{{ asset('storage/' . $member->profile_photo) }}" alt="{{ $member->name }}">
                        @else
                            <div class="mx-auto h-32 w-32 rounded-full bg-blue-500 flex items-center justify-center text-white text-4xl font-bold">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        @endif
                        <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $member->name }}</h3>
                        <p class="text-sm text-gray-500">Member ID: {{ $member->id }}</p>
                    </div>
                </x-card>

                <!-- QR Code -->
                @if($member->qr_code)
                    <x-card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-gray-900">QR Code</h3>
                        </x-slot>
                        <div class="text-center">
                            <!-- QR Code would be displayed here -->
                            <div class="inline-block p-4 bg-gray-100 rounded">
                                <p class="text-xs text-gray-500">QR Code: {{ substr($member->qr_code, 0, 20) }}...</p>
                            </div>
                            <button class="mt-4 btn btn-primary text-sm">Regenerate QR</button>
                        </div>
                    </x-card>
                @endif

                <!-- Quick Actions -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                    </x-slot>
                    <div class="space-y-2">
                        <a href="#" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">View Attendance</a>
                        <a href="#" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">View Progress</a>
                        <a href="#" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">View Invoices</a>
                        <a href="#" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded">Link Parent</a>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>

