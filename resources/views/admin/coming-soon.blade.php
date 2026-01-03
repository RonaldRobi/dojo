<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ $pageTitle ?? 'Coming Soon' }}</h2>
            <p class="text-sm text-gray-500 mt-1">Fitur ini akan segera tersedia</p>
        </div>
    </x-slot>

    <div class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center max-w-md">
            <div class="mb-6">
                <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Coming Soon</h3>
            <p class="text-gray-600 mb-6">
                Fitur <strong>{{ $pageTitle ?? 'ini' }}</strong> sedang dalam pengembangan dan akan segera hadir untuk memberikan pengalaman yang lebih baik.
            </p>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</x-app-layout>

