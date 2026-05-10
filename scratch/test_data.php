<?php

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// 1. Crear una cita para HOY (para el reporte de las 8:00 AM)
$doctor = Doctor::first();
$patient = Patient::first();

if (!$doctor || !$patient) {
    echo "No hay doctores o pacientes. Verifica los seeders.\n";
    exit;
}

Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'date' => Carbon::today()->toDateString(),
    'start_time' => '10:00',
    'end_time' => '11:00',
    'reason' => 'Consulta de prueba para hoy',
    'status' => 'scheduled'
]);

// 2. Crear una cita para MAÑANA (para el recordatorio de WhatsApp)
Appointment::create([
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'date' => Carbon::tomorrow()->toDateString(),
    'start_time' => '15:00',
    'end_time' => '16:00',
    'reason' => 'Consulta de prueba para mañana',
    'status' => 'scheduled'
]);

echo "Citas de prueba creadas.\n";
