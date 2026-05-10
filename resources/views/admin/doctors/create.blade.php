<x-admin-layout tittle="Nuevo Doctor" :breadcrumbs="[
    [
        'name' => 'Dashboard', 
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Nuevo Doctor',
    ],
]">

    <div class="max-w-4xl mx-auto mt-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800">Registrar Nuevo Médico</h2>
                <p class="text-sm text-gray-500">Complete la información para crear la cuenta del doctor.</p>
            </div>

            <form action="{{ route('admin.doctors.store') }}" method="POST" class="p-8">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-xmark text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-bold">Por favor corrija los siguientes errores:</p>
                                <ul class="mt-1 list-disc list-inside text-xs text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información Personal -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider">Información Personal</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Ej. Dr. Roberto Gómez" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="correo@ejemplo.com" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">DNI / Identificación</label>
                            <input type="text" name="id_number" value="{{ old('id_number') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Número de identidad" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Ej. 529991234567" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección del Consultorio</label>
                            <input type="text" name="address" value="{{ old('address') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Av. Principal #123, Clínica Salud" required>
                        </div>
                    </div>

                    <!-- Información Médica -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-bold text-blue-600 uppercase tracking-wider">Información Médica</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                            <select name="specialty" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                                <option value="">Seleccione especialidad</option>
                                <option value="Cardiología" {{ old('specialty') == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                                <option value="Dermatología" {{ old('specialty') == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                                <option value="Endocrinología" {{ old('specialty') == 'Endocrinología' ? 'selected' : '' }}>Endocrinología</option>
                                <option value="Ginecología" {{ old('specialty') == 'Ginecología' ? 'selected' : '' }}>Ginecología</option>
                                <option value="Geriatría" {{ old('specialty') == 'Geriatría' ? 'selected' : '' }}>Geriatría</option>
                                <option value="Hematología" {{ old('specialty') == 'Hematología' ? 'selected' : '' }}>Hematología</option>
                                <option value="Pediatría" {{ old('specialty') == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                                <option value="Medicina General" {{ old('specialty') == 'Medicina General' ? 'selected' : '' }}>Medicina General</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cédula Profesional / Licencia</label>
                            <input type="text" name="license_number" value="{{ old('license_number') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="MED-123456" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('admin.doctors.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                        Registrar Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
