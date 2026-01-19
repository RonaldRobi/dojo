<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Event Reports</h2>
            <p class="text-sm text-gray-500 mt-1">Track event performance and participation</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="GET" action="{{ route('admin.reports.events') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Period -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                        <select name="period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>This Week</option>
                            <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>This Month</option>
                            <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>This Year</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>

                    <!-- Dojo Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dojo</label>
                        <select name="dojo_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">All Dojos</option>
                            @foreach($dojos as $dojo)
                                <option value="{{ $dojo->id }}" {{ $dojoId == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                        <input type="date" name="date_from" value="{{ $dateFrom->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                        <input type="date" name="date_to" value="{{ $dateTo->format('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold">Filter</button>
                    <a href="{{ route('admin.reports.events') }}" class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">Reset</a>
                    
                    <!-- Export Buttons -->
                    <div class="ml-auto flex gap-2">
                        <a href="{{ route('admin.reports.events', array_merge(request()->all(), ['export' => 'csv'])) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            CSV
                        </a>
                        <a href="{{ route('admin.reports.events', array_merge(request()->all(), ['export' => 'pdf'])) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Events</p>
                        <h3 class="text-3xl font-bold mt-2">{{ number_format($totalEvents) }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Participants</p>
                        <h3 class="text-3xl font-bold mt-2">{{ number_format($totalParticipants) }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Event Revenue</p>
                        <h3 class="text-3xl font-bold mt-2">RM {{ number_format($totalRevenue, 0) }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Event Details Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Event Details</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Event Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dojo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Type</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Participants</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Fee</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $event->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $event->dojo->name ?? 'All Dojos' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ ucfirst($event->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-center font-semibold text-blue-600">{{ $event->registrations_count }}</td>
                                <td class="px-6 py-4 text-sm text-right text-gray-500">RM {{ number_format($event->registration_fee ?? 0, 0) }}</td>
                                <td class="px-6 py-4 text-sm text-right font-semibold text-gray-900">RM {{ number_format(($event->registration_fee ?? 0) * $event->registrations_count, 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">No events found for this period</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
