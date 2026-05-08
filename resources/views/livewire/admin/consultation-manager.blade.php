<div x-data="{ tab: 'consulta', showHistoryModal: false, showPreviousModal: false }" class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Breadcrumbs & Header -->
    <div class="mb-6 flex justify-between items-start">
        <div>
            <nav class="text-sm text-gray-500 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:underline">Dashboard</a> / 
                <a href="{{ route('admin.appointments.index') }}" class="hover:underline">Citas</a> / 
                <span class="text-gray-800">Consulta</span>
            </nav>
            <h2 class="text-xl font-bold text-gray-900">Consulta</h2>
            
            <div class="mt-4">
                <h1 class="text-2xl font-bold text-gray-900">{{ $appointment->patient->user->name ?? 'Paciente Desconocido' }}</h1>
                <p class="text-sm text-gray-500 mt-1">DNI: {{ $appointment->patient->user->id_number ?? 'N/A' }}</p>
            </div>
        </div>
        
        <div class="flex space-x-2 mt-8">
            <button @click="showHistoryModal = true" class="bg-white border border-gray-300 text-gray-700 text-sm font-medium py-1.5 px-3 rounded hover:bg-gray-50 flex items-center shadow-sm">
                <i class="fa-solid fa-address-card mr-2 opacity-70"></i> Ver Historia
            </button>
            <button @click="showPreviousModal = true" class="bg-white border border-gray-300 text-gray-700 text-sm font-medium py-1.5 px-3 rounded hover:bg-gray-50 flex items-center shadow-sm">
                <i class="fa-solid fa-clock-rotate-left mr-2 opacity-70"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    <!-- Main Content Box -->
    <div class="bg-white shadow-sm border border-gray-200 sm:rounded-lg overflow-hidden">
        <!-- Tabs -->
        <div class="border-b border-gray-200 px-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="tab = 'consulta'" :class="tab === 'consulta' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors">
                    <i class="fa-regular fa-file-lines mr-2"></i> Consulta
                </button>
                <button @click="tab = 'receta'" :class="tab === 'receta' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center transition-colors">
                    <i class="fa-solid fa-prescription-bottle mr-2"></i> Receta
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Tab Consulta -->
            <div x-show="tab === 'consulta'" class="space-y-6">
                <div>
                    <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">Diagnóstico <span class="text-red-500">*</span></label>
                    <textarea wire:model="diagnosis" id="diagnosis" rows="4" class="block w-full rounded-lg text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('diagnosis') border-red-500 bg-red-50 @else border-gray-300 @enderror" placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                    @error('diagnosis')
                        <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="treatment" class="block text-sm font-medium text-gray-700 mb-2">Tratamiento <span class="text-red-500">*</span></label>
                    <textarea wire:model="treatment" id="treatment" rows="4" class="block w-full rounded-lg text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('treatment') border-red-500 bg-red-50 @else border-gray-300 @enderror" placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                    @error('treatment')
                        <p class="mt-1 text-sm text-red-600 flex items-center"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas <span class="text-xs text-gray-400">(opcional)</span></label>
                    <textarea wire:model="notes" id="notes" rows="3" class="block w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                </div>
                
                @error('medications')
                    <div class="bg-red-50 border border-red-300 rounded-lg p-3 flex items-center text-red-700 text-sm">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                        {{ $message }} Ve a la pestaña <strong class="mx-1">Receta</strong> y añade al menos un medicamento.
                    </div>
                @enderror

                <div class="flex justify-end pt-2">
                    <button wire:click="saveConsultation" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition-colors text-sm flex items-center shadow-sm">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Consulta
                    </button>
                </div>
            </div>

            <!-- Tab Receta -->
            <div x-show="tab === 'receta'" x-cloak>
                <div class="space-y-4 mb-6">
                    <!-- Current Medications -->
                    @foreach($medications as $index => $med)
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-end space-x-4">
                        <div class="flex-grow">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Medicamento</label>
                            <input type="text" value="{{ $med['name'] }}" disabled class="bg-gray-100 block w-full border-gray-300 rounded text-sm text-gray-600">
                        </div>
                        <div class="w-1/4">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dosis</label>
                            <input type="text" value="{{ $med['dosage'] }}" disabled class="bg-gray-100 block w-full border-gray-300 rounded text-sm text-gray-600">
                        </div>
                        <div class="w-1/3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Frecuencia / Duración</label>
                            <input type="text" value="{{ $med['frequency'] }}" disabled class="bg-gray-100 block w-full border-gray-300 rounded text-sm text-gray-600">
                        </div>
                        <button wire:click="removeMedication({{ $index }})" class="bg-red-400 hover:bg-red-500 text-white p-2 rounded transition-colors" title="Eliminar">
                            <i class="fa-solid fa-trash text-sm"></i>
                        </button>
                    </div>
                    @endforeach

                    <!-- Form for new medication -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-end space-x-4">
                        <div class="flex-grow">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Medicamento</label>
                            <input wire:model="newMedication.name" type="text" class="block w-full border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ej. Amoxicilina 500mg">
                        </div>
                        <div class="w-1/4">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Dosis</label>
                            <input wire:model="newMedication.dosage" type="text" class="block w-full border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ej. 1 cada 8 horas">
                        </div>
                        <div class="w-1/3">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Frecuencia / Duración</label>
                            <input wire:model="newMedication.frequency" type="text" class="block w-full border-gray-300 rounded text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ej. cada 8 horas por 7 días">
                        </div>
                        <button wire:click="addMedication" class="bg-blue-400 hover:bg-blue-500 text-white p-2 rounded transition-colors" title="Añadir">
                            <i class="fa-solid fa-plus text-sm"></i>
                        </button>
                    </div>
                    
                    <div>
                        <button wire:click="addMedication" class="bg-white border border-gray-300 text-gray-600 text-sm font-medium py-1.5 px-3 rounded hover:bg-gray-50 flex items-center transition-colors">
                            <i class="fa-solid fa-plus mr-2"></i> Añadir Medicamento
                        </button>
                    </div>
                </div>

                @error('medications')
                    <div class="bg-red-50 border border-red-300 rounded-lg p-3 flex items-center text-red-700 text-sm mb-4">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ $message }}
                    </div>
                @enderror

                <div class="flex justify-end pt-2 mt-8">
                    <button wire:click="saveConsultation" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition-colors text-sm flex items-center shadow-sm">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Guardar Consulta
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Historia Médica -->
    <div x-show="showHistoryModal" x-cloak class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showHistoryModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showHistoryModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showHistoryModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-6 pt-5 pb-6">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                        <h3 class="text-lg font-bold text-gray-800" id="modal-title">
                            Historia médica del paciente
                        </h3>
                        <button @click="showHistoryModal = false" class="text-gray-400 hover:text-gray-500">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 my-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Tipo de sangre:</p>
                            <p class="text-sm font-bold text-gray-900">{{ $appointment->patient->blood_type->name ?? 'A-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Alergias:</p>
                            <p class="text-sm font-bold text-gray-900">{{ $appointment->patient->allergies ?? 'No registradas' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Enfermedades crónicas:</p>
                            <p class="text-sm font-bold text-gray-900">{{ $appointment->patient->chronic_conditions ?? 'No registradas' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 mb-1">Antecedentes quirúrgicos:</p>
                            <p class="text-sm font-bold text-gray-900">{{ $appointment->patient->surgical_history ?? 'No registradas' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.patients.edit', $appointment->patient_id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                            Ver / Editar Historia Médica
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Consultas Anteriores -->
    <div x-show="showPreviousModal" x-cloak class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showPreviousModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showPreviousModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showPreviousModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-6 pt-5 pb-6">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                        <h3 class="text-lg font-bold text-gray-800" id="modal-title">
                            Consultas Anteriores
                        </h3>
                        <button @click="showPreviousModal = false" class="text-gray-400 hover:text-gray-500">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <div class="my-6 max-h-[60vh] overflow-y-auto pr-2 space-y-4">
                        @forelse($previousConsultations as $prev)
                        <div class="border border-purple-200 rounded-lg p-4 bg-white shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-bold text-blue-900 flex items-center">
                                        <i class="fa-regular fa-calendar-days mr-2"></i> 
                                        {{ \Carbon\Carbon::parse($prev->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($prev->start_time)->format('H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">Atendido por: Dr(a). {{ $prev->doctor->user->name ?? 'Desconocido' }}</p>
                                </div>
                                <button class="border border-purple-300 text-purple-600 hover:bg-purple-50 text-xs font-medium py-1.5 px-3 rounded transition-colors">
                                    Consultar Detalle
                                </button>
                            </div>
                            
                            <div class="bg-gray-50 p-3 rounded text-sm text-gray-700 space-y-2 border border-gray-100">
                                <p><span class="font-bold text-gray-800">Diagnóstico:</span> {{ $prev->diagnosis ?? 'No registrado' }}</p>
                                <p><span class="font-bold text-gray-800">Tratamiento:</span> {{ $prev->treatment ?? 'No registrado' }}</p>
                                <p><span class="font-bold text-gray-800">Notas:</span> {{ $prev->notes ?? 'Sin notas adicionales' }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fa-solid fa-notes-medical text-3xl mb-2 text-gray-300"></i>
                            <p>No hay consultas anteriores registradas para este paciente.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
