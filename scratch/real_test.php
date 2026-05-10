<?php

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use App\Services\WhatsAppService;
use Carbon\Carbon;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Configurar el usuario para la prueba real
$user = User::whereHas('patient')->first();
if ($user) {
    $user->phone = '529993526801'; // El número que aparece en tu imagen de Meta
    $user->save();
    
    $patient = $user->patient;
    $doctor = Doctor::first();

    echo "Iniciando prueba real para el número: {$user->phone}...\n";

    $appointment = Appointment::create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'date' => Carbon::today()->toDateString(),
        'start_time' => '12:00',
        'end_time' => '13:00',
        'reason' => 'Prueba de conexión real con WhatsApp Meta API',
        'status' => 'scheduled'
    ]);

    $whatsAppService = new WhatsAppService();
    $result = $whatsAppService->sendTemplateMessage($user->phone, 'hello_world');

    if ($result) {
        echo "¡Éxito! El comando se envió a Meta. Revisa tu celular.\n";
    } else {
        echo "Hubo un error al enviar. Revisa storage/logs/laravel.log para más detalles.\n";
    }
} else {
    echo "No se encontró un paciente para la prueba.\n";
}
