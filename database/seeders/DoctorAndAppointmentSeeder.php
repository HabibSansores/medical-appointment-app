<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorAndAppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Crear 10 Doctores
        $doctors = [];
        $specialties = ['Cardiología', 'Dermatología', 'Endocrinología', 'Ginecología', 'Geriatría', 'Hematología', 'Pediatría'];
        
        for ($i = 1; $i <= 10; $i++) {
            $user = User::factory()->create([
                'name' => 'Doctor Demo ' . $i,
                'email' => 'doctor' . $i . '@demo.com',
                'password' => bcrypt('password'),
                'id_number' => '5000000' . $i,
                'phone' => '60000000' . $i,
                'address' => 'Clínica Demo ' . $i,
            ]);
            $user->assignRole('Doctor');

            $doctor = \App\Models\Doctor::create([
                'user_id' => $user->id,
                'specialty' => $specialties[array_rand($specialties)],
                'license_number' => 'MED-' . rand(1000, 9999),
            ]);
            $doctors[] = $doctor;
        }

        // Asegurar que hay al menos 10 Pacientes
        $patients = \App\Models\Patient::all();
        if ($patients->count() < 10) {
            for ($i = 1; $i <= 10; $i++) {
                $user = User::factory()->create([
                    'name' => 'Paciente Demo ' . $i,
                    'email' => 'paciente' . $i . '@demo.com',
                    'password' => bcrypt('password'),
                    'id_number' => '3000000' . $i,
                    'phone' => '33333333' . $i,
                    'address' => 'Calle Demo ' . $i,
                ]);
                $user->assignRole('Paciente');

                $patient = \App\Models\Patient::create([
                    'user_id' => $user->id,
                    'blood_type_id' => 1, // Asumiendo que existe el ID 1
                    'allergies' => 'Ninguna',
                    'chronic_conditions' => 'Ninguna',
                ]);
                $patients->push($patient);
            }
        }

        // Crear Citas
        $statuses = [1, 2, 3]; // 1: Programado, 2: Completado, 3: Cancelado
        foreach ($patients as $patient) {
            // Asignar entre 1 y 3 citas a cada paciente
            $numCitas = rand(1, 3);
            for ($j = 0; $j < $numCitas; $j++) {
                $doctor = $doctors[array_rand($doctors)];
                \App\Models\Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'date' => \Carbon\Carbon::today()->addDays(rand(-5, 15))->format('Y-m-d'),
                    'start_time' => sprintf('%02d:00', rand(8, 17)),
                    'end_time' => sprintf('%02d:30', rand(8, 17)),
                    'duration' => 30,
                    'reason' => 'Consulta general de rutina',
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }
}
