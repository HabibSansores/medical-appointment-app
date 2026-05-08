<x-admin-layout tittle="Nuevo" :breadcrumbs="[
    [
        'name' => 'Dashboard', 
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">

    <form action="{{ route('admin.appointments.store') }}" method="POST" class="mt-4">
        @csrf

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Disponibilidad -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Search Box -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">Buscar disponibilidad</h3>
                    <p class="text-sm text-gray-500 mb-4">Encuentra el horario perfecto para tu cita.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Especialidad</label>
                            <select id="specialtyFilter" class="block w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todas</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty }}">{{ $specialty }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Fecha</label>
                            <div class="relative">
                                <input type="date" id="appointmentDate" name="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}" class="block w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Hora Inicio y Fin</label>
                            <div class="flex items-center space-x-2">
                                <select id="startTime" name="start_time" class="block w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Inicio</option>
                                    @for($h = 8; $h <= 20; $h++)
                                        @foreach(['00', '15', '30', '45'] as $m)
                                            @php $time = sprintf('%02d:%s', $h, $m); @endphp
                                            <option value="{{ $time }}" {{ old('start_time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                        @endforeach
                                    @endfor
                                </select>
                                <span class="text-gray-400">-</span>
                                <select id="endTime" name="end_time" class="block w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Fin</option>
                                    @for($h = 8; $h <= 20; $h++)
                                        @foreach(['00', '15', '30', '45'] as $m)
                                            @php $time = sprintf('%02d:%s', $h, $m); @endphp
                                            <option value="{{ $time }}" {{ old('end_time') == $time ? 'selected' : '' }}>{{ $time }}</option>
                                        @endforeach
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <button type="button" id="searchBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md transition-colors text-sm">
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Doctor Cards -->
                <div class="space-y-4" id="doctorsList">
                    @foreach($doctors as $doctor)
                    <label class="block cursor-pointer doctor-card" data-specialty="{{ $doctor->specialty }}" data-schedule="{{ json_encode($doctor->schedule ?? []) }}">
                        <input type="radio" name="doctor_id" value="{{ $doctor->id }}" class="peer sr-only" {{ old('doctor_id') == $doctor->id ? 'checked' : '' }}>
                        <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100 peer-checked:ring-2 peer-checked:ring-blue-500 transition-all">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
                                    {{ substr($doctor->user->name ?? 'D', 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-gray-800">Dr. {{ $doctor->user->name ?? 'Desconocido' }}</h4>
                                    <p class="text-xs text-blue-600 font-medium">{{ $doctor->specialty ?? 'Especialista' }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray-500 mb-2 font-medium">Horarios disponibles:</p>
                                <div class="flex flex-wrap gap-2">
                                    @if(is_array($doctor->schedule) && count($doctor->schedule) > 0)
                                        @foreach($doctor->schedule as $day => $times)
                                            @if(is_array($times) && count($times) > 0)
                                                @php
                                                    sort($times);
                                                    $start = substr($times[0], 0, 5);
                                                    $end = substr(end($times), -5);
                                                @endphp
                                                <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-md border border-blue-100 shadow-sm">
                                                    {{ $day }}: {{ $start }} a {{ $end }}
                                                </span>
                                            @endif
                                        @endforeach
                                        @if(empty(array_filter($doctor->schedule)))
                                            <span class="bg-gray-50 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-md border border-gray-200">
                                                Sin horario asignado
                                            </span>
                                        @endif
                                    @else
                                        <span class="bg-gray-50 text-gray-500 text-xs font-semibold px-2.5 py-1 rounded-md border border-gray-200">
                                            Sin horario asignado
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Right Side: Resumen -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Resumen de la cita</h3>
                    
                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">Doctor:</span>
                            <span class="font-medium text-gray-800" id="summaryDoctor">Seleccionar en la lista</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">Fecha:</span>
                            <span class="font-medium text-gray-800" id="summaryDate">Definir fecha</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">Horario:</span>
                            <span class="font-medium text-gray-800" id="summaryTime">Definir horario</span>
                        </div>
                        <div class="flex justify-between pb-2">
                            <span class="text-gray-500">Duración:</span>
                            <span class="font-medium text-gray-800" id="summaryDuration">0 minutos</span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Paciente</label>
                            <select name="patient_id" class="block w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione un paciente</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->user->name ?? 'Desconocido' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Motivo de la cita</label>
                            <textarea name="reason" rows="3" class="block w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ej. Chequeo de medicamentos" required>{{ old('reason') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-md transition-colors text-sm shadow-sm">
                        Confirmar cita
                    </button>
                </div>
            </div>
        </div>
    </form>

</x-admin-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const specialtyFilter = document.getElementById('specialtyFilter');
        const doctorCards = document.querySelectorAll('.doctor-card');
        const dateInput = document.getElementById('appointmentDate');
        const startTimeInput = document.getElementById('startTime');
        const endTimeInput = document.getElementById('endTime');
        const doctorRadios = document.querySelectorAll('input[name="doctor_id"]');
        
        const summaryDoctor = document.getElementById('summaryDoctor');
        const summaryDate = document.getElementById('summaryDate');
        const summaryTime = document.getElementById('summaryTime');
        const summaryDuration = document.getElementById('summaryDuration');

        // Logic for restricting time based on selected date
        function updateTimeConstraints() {
            if (!dateInput || !startTimeInput) return;
            
            const today = new Date();
            const todayString = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
            
            if (dateInput.value === todayString) {
                const currentHour = String(today.getHours()).padStart(2, '0');
                const currentMinute = String(today.getMinutes()).padStart(2, '0');
                const currentTime = `${currentHour}:${currentMinute}`;
                
                let firstValidFound = false;
                
                // Hide past options in the select
                Array.from(startTimeInput.options).forEach(option => {
                    if (option.value && option.value < currentTime) {
                        option.disabled = true;
                        option.style.display = 'none';
                    } else {
                        option.disabled = false;
                        option.style.display = 'block';
                    }
                });

                // Clear selection if current selection is disabled
                if (startTimeInput.value && startTimeInput.value < currentTime) {
                    startTimeInput.value = '';
                }
            } else {
                // Enable all options
                Array.from(startTimeInput.options).forEach(option => {
                    option.disabled = false;
                    option.style.display = 'block';
                });
            }
        }

        // Logic for dynamically updating the appointment summary
        function updateSummary() {
            // Update Doctor
            const selectedDoctor = document.querySelector('input[name="doctor_id"]:checked');
            if (selectedDoctor) {
                const card = selectedDoctor.closest('.doctor-card');
                const doctorName = card.querySelector('h4').textContent;
                summaryDoctor.textContent = doctorName;
            } else {
                summaryDoctor.textContent = 'Seleccionar en la lista';
            }

            // Update Date
            if (dateInput && dateInput.value) {
                summaryDate.textContent = dateInput.value;
            } else {
                summaryDate.textContent = 'Definir fecha';
            }

            // Update Time and Duration
            if (startTimeInput && endTimeInput && startTimeInput.value && endTimeInput.value) {
                summaryTime.textContent = `${startTimeInput.value} - ${endTimeInput.value}`;
                
                const startParts = startTimeInput.value.split(':');
                const endParts = endTimeInput.value.split(':');
                const startDate = new Date();
                startDate.setHours(startParts[0], startParts[1], 0);
                const endDate = new Date();
                endDate.setHours(endParts[0], endParts[1], 0);
                
                let diffMins = Math.round((endDate - startDate) / 60000);
                if (diffMins < 0) diffMins = 0; 
                
                summaryDuration.textContent = `${diffMins} minutos`;
            } else {
                summaryTime.textContent = 'Definir horario';
                summaryDuration.textContent = '0 minutos';
            }
        }

        if (dateInput) {
            dateInput.addEventListener('change', () => {
                updateTimeConstraints();
                updateSummary();
            });
            updateTimeConstraints(); 
        }

        if (startTimeInput) startTimeInput.addEventListener('change', updateSummary);
        if (endTimeInput) endTimeInput.addEventListener('change', updateSummary);
        doctorRadios.forEach(radio => radio.addEventListener('change', updateSummary));

        // Logic to generate 15 min slots from start to end time
        function generateRequiredSlots(start, end) {
            let slots = [];
            let [sH, sM] = start.split(':').map(Number);
            let [eH, eM] = end.split(':').map(Number);
            
            let current = new Date();
            current.setHours(sH, sM, 0, 0);
            let endDateTime = new Date();
            endDateTime.setHours(eH, eM, 0, 0);
            
            while(current < endDateTime) {
                let startSlotH = String(current.getHours()).padStart(2, '0');
                let startSlotM = String(current.getMinutes()).padStart(2, '0');
                
                current.setMinutes(current.getMinutes() + 15);
                
                let endSlotH = String(current.getHours()).padStart(2, '0');
                let endSlotM = String(current.getMinutes()).padStart(2, '0');
                
                slots.push(`${startSlotH}:${startSlotM} - ${endSlotH}:${endSlotM}`);
            }
            return slots;
        }

        function filterDoctors() {
            const selectedSpecialty = specialtyFilter ? specialtyFilter.value : '';
            const selectedDate = dateInput ? dateInput.value : '';
            const startTime = startTimeInput ? startTimeInput.value : '';
            const endTime = endTimeInput ? endTimeInput.value : '';

            // Get Day of Week
            let dayOfWeekStr = '';
            if (selectedDate) {
                // Ensure correct timezone parsing
                const [year, month, day] = selectedDate.split('-');
                const d = new Date(year, month - 1, day);
                const daysMap = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                dayOfWeekStr = daysMap[d.getDay()];
            }

            let requiredSlots = [];
            if (startTime && endTime && startTime < endTime) {
                requiredSlots = generateRequiredSlots(startTime, endTime);
            }

            doctorCards.forEach(card => {
                let showCard = true;

                // 1. Check Specialty
                if (selectedSpecialty !== '' && card.dataset.specialty !== selectedSpecialty) {
                    showCard = false;
                }

                // 2. Check Schedule if Date and Time are selected
                if (showCard && requiredSlots.length > 0 && dayOfWeekStr) {
                    try {
                        const schedule = JSON.parse(card.dataset.schedule || '{}');
                        const daySchedule = schedule[dayOfWeekStr] || [];
                        
                        // Check if the doctor has ALL required slots for that day
                        const hasAllSlots = requiredSlots.every(slot => daySchedule.includes(slot));
                        if (!hasAllSlots) {
                            showCard = false;
                        }
                    } catch (e) {
                        showCard = false; // Invalid JSON means no schedule
                    }
                }

                card.style.display = showCard ? 'block' : 'none';
                
                // If a card gets hidden but was checked, uncheck it to clear selection
                if (!showCard) {
                    const radio = card.querySelector('input[type="radio"]');
                    if (radio.checked) {
                        radio.checked = false;
                        updateSummary(); // Clear it from summary
                    }
                }
            });
        }

        const searchBtn = document.getElementById('searchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', filterDoctors);
        }

        if(specialtyFilter) {
            specialtyFilter.addEventListener('change', filterDoctors);
        }
        
        // Form submission validation for reason
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const reasonField = document.querySelector('textarea[name="reason"]');
                if (reasonField) {
                    const reasonText = reasonField.value.trim();
                    const wordCount = reasonText ? reasonText.split(/\s+/).length : 0;
                    
                    if (wordCount < 5) {
                        e.preventDefault();
                        alert('Debe escribir el motivo de la cita con un mínimo de 5 palabras.');
                        reasonField.focus();
                    }
                }
            });
        }
        
        // Initial call to set summary on page load
        updateSummary();
        filterDoctors();
    });
</script>
