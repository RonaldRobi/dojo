<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h2>
                <p class="text-sm text-gray-600 mt-1">Announcement Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('owner.announcements.edit', $announcement) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('owner.announcements.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <dl class="grid grid-cols-1 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Title</dt>
                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $announcement->title }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Target Audience</dt>
                <dd class="mt-1">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                        {{ str_replace('_', ' ', $announcement->target_audience) }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Priority</dt>
                <dd class="mt-1">
                    @php
                        $priorityColors = [
                            'low' => 'bg-gray-100 text-gray-800',
                            'normal' => 'bg-blue-100 text-blue-800',
                            'high' => 'bg-red-100 text-red-800',
                        ];
                        $color = $priorityColors[$announcement->priority] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                        {{ $announcement->priority }}
                    </span>
                </dd>
            </div>
            @if($announcement->publish_at)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Publish Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $announcement->publish_at->format('M d, Y g:i A') }}</dd>
                </div>
            @endif
            @if($announcement->expires_at)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Expires At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $announcement->expires_at->format('M d, Y g:i A') }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-sm font-medium text-gray-500">Status</dt>
                <dd class="mt-1">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $announcement->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $announcement->is_published ? 'Published' : 'Draft' }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Content</dt>
                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $announcement->content }}</dd>
            </div>
        </dl>
    </div>
</x-app-layout>

