<x-admin-layout tittle="Horarios" :breadcrumbs="[
    [
        'name' => 'Dashboard', 
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Horarios',
    ],
]">

    @php
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves'];
        $hours = ['08', '09', '10', '11'];
        $intervals = [
            '00' => '15',
            '15' => '30',
            '30' => '45',
            '45' => '00'
        ];
        
        $savedSchedule = old('schedule', $doctor->schedule ?? []);
    @endphp

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mt-4 p-6">
        <form action="{{ route('admin.doctors.schedule.update', $doctor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Gestor de horarios</h2>
                    <p class="text-sm text-gray-500">Dr(a). {{ $doctor->user->name ?? 'Desconocido' }}</p>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Guardar horario
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-4 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">DÍA/HORA</th>
                            @foreach($days as $day)
                                <th class="py-4 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($hours as $hour)
                            @php
                                $nextHour = str_pad((int)$hour + 1, 2, '0', STR_PAD_LEFT);
                            @endphp
                            <tr>
                                <td class="py-6 px-4 align-top">
                                    <div class="flex items-center text-sm font-medium text-gray-900 mt-1">
                                        <input type="checkbox" class="hour-toggle w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2" data-hour="{{ $hour }}">
                                        {{ $hour }}:00:00
                                    </div>
                                </td>

                                @foreach($days as $day)
                                    <td class="py-6 px-4 align-top">
                                        <div class="space-y-3">
                                            <div class="flex items-center">
                                                <input type="checkbox" class="day-hour-toggle w-4 h-4 text-gray-600 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 mr-2" data-day="{{ $day }}" data-hour="{{ $hour }}">
                                                <span class="text-xs text-gray-600">Todos</span>
                                            </div>

                                            @foreach($intervals as $startMin => $endMin)
                                                @php
                                                    $endHourBlock = ($endMin == '00') ? $nextHour : $hour;
                                                    $timeSlot = "{$hour}:{$startMin} - {$endHourBlock}:{$endMin}";
                                                    $isChecked = isset($savedSchedule[$day]) && in_array($timeSlot, $savedSchedule[$day]);
                                                @endphp
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="schedule[{{ $day }}][]" value="{{ $timeSlot }}" class="slot-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2" data-day="{{ $day }}" data-hour="{{ $hour }}" {{ $isChecked ? 'checked' : '' }}>
                                                    <span class="text-sm text-gray-700">{{ $timeSlot }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // "Todos" checkbox logic for a specific Day and Hour block
            document.querySelectorAll('.day-hour-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const day = this.dataset.day;
                    const hour = this.dataset.hour;
                    const isChecked = this.checked;
                    
                    document.querySelectorAll(`.slot-checkbox[data-day="${day}"][data-hour="${hour}"]`).forEach(cb => {
                        cb.checked = isChecked;
                    });
                });
            });

            // "Hour" checkbox logic for all Days in that Hour row
            document.querySelectorAll('.hour-toggle').forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const hour = this.dataset.hour;
                    const isChecked = this.checked;

                    // Check all "Todos" in this row
                    document.querySelectorAll(`.day-hour-toggle[data-hour="${hour}"]`).forEach(cb => {
                        cb.checked = isChecked;
                    });

                    // Check all slots in this row
                    document.querySelectorAll(`.slot-checkbox[data-hour="${hour}"]`).forEach(cb => {
                        cb.checked = isChecked;
                    });
                });
            });
        });
    </script>
</x-admin-layout>
