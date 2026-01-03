<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $rank->name }}</h2>
                <p class="text-sm text-gray-600 mt-1">Rank Details</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('owner.ranks.edit', $rank) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit
                </a>
                <a href="{{ route('owner.ranks.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <dl class="grid grid-cols-1 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Rank Name</dt>
                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $rank->name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Level</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $rank->level }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Belt Color</dt>
                <dd class="mt-1">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full" style="background-color: {{ $rank->color }}; color: white;">
                        {{ $rank->color }}
                    </span>
                </dd>
            </div>
            @if($rank->requirements)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Requirements</dt>
                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $rank->requirements }}</dd>
                </div>
            @endif
            @if($rank->description)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $rank->description }}</dd>
                </div>
            @endif
        </dl>
    </div>
</x-app-layout>

