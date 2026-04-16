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
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200"
                               :aria-current="tab === 'datos-personales' ? 'page' : undefined">
                                <i class="fa-solid fa-user mr-2"></i>
                                Datos Personales
                            </a>
                        </li>

                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'antecedentes'"
                               :class="tab === 'antecedentes' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200"
                               :aria-current="tab === 'antecedentes' ? 'page' : undefined">
                                <i class="fa-solid fa-file-lines mr-2"></i>
                                Antecedentes
                            </a>
                        </li>

                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'informacion-general'"
                               :class="tab === 'informacion-general' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200"
                               :aria-current="tab === 'informacion-general' ? 'page' : undefined">
                                <i class="fa-solid fa-info mr-2"></i>
                                Información General
                            </a>
                        </li>

                        <li class="me-2">
                            <a href="#" x-on:click.prevent="tab = 'contacto-emergencia'"
                               :class="tab === 'contacto-emergencia' ? 'text-blue-600 border-blue-600' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                               class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg transition-colors duration-200"
                               :aria-current="tab === 'contacto-emergencia' ? 'page' : undefined">
                                <i class="fa-solid fa-heart mr-2"></i>
                                Contacto de Emergencia
                            </a>
                        </li>

                    </ul>
                </div>

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
                                        <p>La <strong>información de acceso</strong> (nombre, email y contraseña)
                                        debe gestionarse desde la cuenta de usuario asociada.</p>
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
            </div> {{-- Cierre x-data --}}
        </x-wire-card>

    </form>
</x-admin-layout>