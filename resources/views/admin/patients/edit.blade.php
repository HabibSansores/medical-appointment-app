<x-admin-layout title="Pacientes" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => 'Editar'],
]">

    <form action="{{ route('admin.patients.update', $patient) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Header --}}
        <x-wire-card>
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ $patient->user->profile_photo_url }}"
                         alt="{{ $patient->user->name }}"
                         class="w-20 h-20 rounded-full object-cover object-center">
                    <div class="ml-4">
                        <p class="text-2xl font-bold">{{ $patient->user->name }}</p>
                    </div>
                </div>
                <div class="flex gap-4 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">Volver</x-wire-button>
                    <x-wire-button type="submit" primary>
                        <i class="fa-solid fa-check mr-2"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        {{-- Tabs --}}
        <x-wire-card>
            <div x-data="{ tab: 'datos-personales' }">

                {{-- Menú de pestañas --}}
                <div class="border-b border-gray-200 mb-6">
                    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'datos-personales'"
                               :class="tab === 'datos-personales' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                <i class="fa-solid fa-user mr-2"></i>
                                Datos Personales
                            </a>
                        </li>

                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'antecedentes'"
                               :class="tab === 'antecedentes' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                <i class="fa-solid fa-file-lines mr-2"></i>
                                Antecedentes
                            </a>
                        </li>

                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'informacion-general'"
                               :class="tab === 'informacion-general' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                <i class="fa-solid fa-info mr-2"></i>
                                Información General
                            </a>
                        </li>

                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'contacto-emergencia'"
                               :class="tab === 'contacto-emergencia' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                <i class="fa-solid fa-heart mr-2"></i>
                                Contacto de Emergencia
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- Contenedor de Contenidos --}}
                <div class="mt-4">
                    {{-- Tab 1: Datos Personales --}}
                    <div x-show="tab === 'datos-personales'">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-1"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-bold text-blue-800">Edición de cuenta de usuario</h3>
                                        <div class="mt-1 text-sm text-blue-600">
                                            <p>La <strong>información de acceso</strong> debe gestionarse desde la cuenta de usuario asociada.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <x-wire-button primary sm href="{{ route('admin.users.edit', $patient->user) }}" target="_blank">
                                        Editar usuario
                                        <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                                    </x-wire-button>
                                </div>
                            </div>
                        </div>
                        <div class="grid lg:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-500 font-semibold">Teléfono:</span>
                                <span class="text-gray-500 text-sm ml-1">{{ $patient->user->phone }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 font-semibold">Email:</span>
                                <span class="text-gray-500 text-sm ml-1">{{ $patient->user->email }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 font-semibold">Dirección:</span>
                                <span class="text-gray-500 text-sm ml-1">{{ $patient->user->address }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tab 2: Antecedentes --}}
                    <div x-show="tab === 'antecedentes'" style="display: none;">
                        <div class="grid lg:grid-cols-2 gap-4">
                            <x-wire-textarea label="Alergias" name="allergies">
                                {{ old('allergies', $patient->allergies) }}
                            </x-wire-textarea>
                            <x-wire-textarea label="Enfermedades crónicas" name="chronic_conditions">
                                {{ old('chronic_conditions', $patient->chronic_conditions) }}
                            </x-wire-textarea>
                            <x-wire-textarea label="Antecedentes quirúrgicos" name="surgical_history">
                                {{ old('surgical_history', $patient->surgical_history) }}
                            </x-wire-textarea>
                            <x-wire-textarea label="Antecedentes familiares" name="family_history">
                                {{ old('family_history', $patient->family_history) }}
                            </x-wire-textarea>
                        </div>
                    </div>

                    {{-- Tab 3: Información General --}}
                    <div x-show="tab === 'informacion-general'" style="display: none;">
                        <x-wire-native-select label="Tipo de sangre" class="mb-4" name="blood_type_id">
                            <option value="">Selecciona un tipo de sangre</option>
                            @foreach($bloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                                    {{ $bloodType->name }}
                                </option>
                            @endforeach
                        </x-wire-native-select>

                        <x-wire-textarea label="Observaciones" name="observations">
                            {{ old('observations', $patient->observations) }}
                        </x-wire-textarea>
                    </div>

                    {{-- Tab 4: Contacto de Emergencia --}}
                    <div x-show="tab === 'contacto-emergencia'" style="display: none;">
                        <div class="space-y-4">
                            <x-wire-input label="Nombre de contacto" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />
                            <x-wire-phone label="Teléfono de contacto" name="emergency_contact_phone" mask="(###) ###-####" placeholder="(999) 999-9999" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" />
                            <x-wire-input label="Relación con el contacto" name="emergency_contact_relationship" placeholder="Familiar, Amigo, etc." value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" />
                        </div>
                    </div>
                </div> {{-- Cierre de contenedor de contenidos --}}
            </div> {{-- Cierre de x-data --}}
        </x-wire-card>

    </form>
</x-admin-layout>