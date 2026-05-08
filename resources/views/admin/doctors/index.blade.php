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
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Doctores</h2>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p>{{ session('success') }}</p>
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
                <button class="bg-white border border-gray-300 text-gray-700 text-sm rounded-lg px-4 py-2 hover:bg-gray-50 flex items-center">
                    10 <i class="fa-solid fa-chevron-down ml-2 text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider flex items-center">
                            ID <i class="fa-solid fa-sort ml-1 opacity-50"></i>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">NOMBRE <i class="fa-solid fa-sort ml-1 opacity-50"></i></div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">EMAIL <i class="fa-solid fa-sort ml-1 opacity-50"></i></div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">DNI <i class="fa-solid fa-sort ml-1 opacity-50"></i></div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">TELEFONO <i class="fa-solid fa-sort ml-1 opacity-50"></i></div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">ESPECIALIDAD <i class="fa-solid fa-sort ml-1 opacity-50"></i></div>
                        </th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->user->phone ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $doctor->specialty ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex justify-center space-x-2">
                                <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white rounded p-1.5 transition-colors" title="Editar">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <a href="{{ route('admin.doctors.schedule', $doctor) }}" class="bg-green-500 hover:bg-green-600 text-white rounded p-1.5 transition-colors" title="Horarios">
                                    <i class="fa-regular fa-clock text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No hay doctores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination footer -->
        <div class="px-6 py-4 bg-white border-t border-gray-200 flex items-center justify-between">
            <span class="text-sm text-gray-700">Mostrando 1 a {{ count($doctors) }} de {{ count($doctors) }} resultados</span>
            <div class="flex">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</x-admin-layout>
