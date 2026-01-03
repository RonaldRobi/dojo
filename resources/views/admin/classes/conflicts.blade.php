<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Schedule Conflicts</h2>
            <p class="text-sm text-gray-500 mt-1">Detect instructor schedule conflicts</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Conflicts</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Schedule</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Instructor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Conflicting Schedule</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conflicts as $conflict)
                        @php
                            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                            $dayName = $days[$conflict['schedule']->day_of_week] ?? '-';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $conflict['schedule']->dojoclass->name ?? '-' }}<br>
                                <span class="text-xs text-gray-500">{{ $dayName }} {{ $conflict['schedule']->start_time ? \Carbon\Carbon::parse($conflict['schedule']->start_time)->format('H:i') : '-' }}-{{ $conflict['schedule']->end_time ? \Carbon\Carbon::parse($conflict['schedule']->end_time)->format('H:i') : '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $conflict['instructor']->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @foreach($conflict['conflicting'] as $conf)
                                    @php
                                        $confDayName = $days[$conf->day_of_week] ?? '-';
                                    @endphp
                                    {{ $conf->dojoclass->name ?? '-' }}<br>
                                    <span class="text-xs text-gray-500">{{ $confDayName }} {{ $conf->start_time ? \Carbon\Carbon::parse($conf->start_time)->format('H:i') : '-' }}-{{ $conf->end_time ? \Carbon\Carbon::parse($conf->end_time)->format('H:i') : '-' }}</span><br>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500">No conflicts found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

