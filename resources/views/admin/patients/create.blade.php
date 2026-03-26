<x-admin-layout tittle="Pacientes" :breadcrumbs="[
    [
        'name' => 'Dashboard', 
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Patients',
        'href' => route('admin.patients.index'),
    ],
    [
        'name' => 'Nuevo',
    ],
]">
</x-admin-layout>