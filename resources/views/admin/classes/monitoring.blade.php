<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Schedule Monitoring</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor class schedules across all branches</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Schedules</h3>
        </div>
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex gap-4">
                <select name="day_of_week" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Days</option>
                    <option value="0" {{ request('day_of_week') == '0' ? 'selected' : '' }}>Sunday</option>
                    <option value="1" {{ request('day_of_week') == '1' ? 'selected' : '' }}>Monday</option>
                    <option value="2" {{ request('day_of_week') == '2' ? 'selected' : '' }}>Tuesday</option>
                    <option value="3" {{ request('day_of_week') == '3' ? 'selected' : '' }}>Wednesday</option>
                    <option value="4" {{ request('day_of_week') == '4' ? 'selected' : '' }}>Thursday</option>
                    <option value="5" {{ request('day_of_week') == '5' ? 'selected' : '' }}>Friday</option>
                    <option value="6" {{ request('day_of_week') == '6' ? 'selected' : '' }}>Saturday</option>
                </select>
                <select name="dojo_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                    <option value="">All Dojos</option>
                    @foreach($dojos as $dojo)
                        <option value="{{ $dojo->id }}" {{ request('dojo_id') == $dojo->id ? 'selected' : '' }}>{{ $dojo->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Day</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Instructor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Dojo</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($schedules as $schedule)
                        @php
                            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            $dayName = $days[$schedule->day_of_week] ?? '-';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $dayName }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $schedule->dojoclass->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '-' }} - {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule->instructor->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $schedule->dojoclass->dojo->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">{{ $schedules->links() }}</div>
    </div>
</x-app-layout>

