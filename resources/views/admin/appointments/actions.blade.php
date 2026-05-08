<div class="flex items-center space-x-2">
    <!-- Botón de Editar -->
    <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white rounded p-1.5 transition-colors" title="Editar">
        <i class="fa-solid fa-pen text-xs"></i>
    </a>
    
    <!-- Botón de Consulta Médica -->
    <a href="{{ route('admin.appointments.consultation', $appointment) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded p-1.5 transition-colors" title="Atender Cita">
        <i class="fa-solid fa-stethoscope text-xs"></i>
    </a>
</div>
