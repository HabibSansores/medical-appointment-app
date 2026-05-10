<x-admin-layout tittle="Doctores" :breadcrumbs="[
    [
        'name' => 'Dashboard', 
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
    ],
]">

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mt-4">
        <!-- Top Header section -->
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Doctores</h2>
            <a href="{{ route('admin.doctors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Nuevo Doctor
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 m-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filters section -->
        <div class="p-4 flex justify-between items-center bg-white">
            <div class="relative w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-search text-gray-400 text-sm"></i>
                </div>
                <input type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2" placeholder="Buscar">
            </div>
            
            <div class="flex space-x-2">
                <button class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg px-4 py-2 hover:bg-gray-50 flex items-center">
                    Columnas <i class="fa-solid fa-chevron-down ml-2 text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NOMBRE</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">EMAIL</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">DNI</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ESPECIALIDAD</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">ACCIONES</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($doctors as $doctor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->user->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->user->id_number ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                    {{ $doctor->specialty ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.doctors.schedule', $doctor) }}" class="bg-green-100 text-green-600 hover:bg-green-600 hover:text-white rounded p-1.5 transition-all" title="Horarios">
                                        <i class="fa-regular fa-clock text-sm"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este doctor? Esta acción también eliminará su cuenta de usuario.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white rounded p-1.5 transition-all" title="Eliminar">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                                No hay doctores registrados en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <span class="text-xs text-gray-500">Mostrando {{ $doctors->count() }} resultados</span>
        </div>
    </div>
</x-admin-layout>
