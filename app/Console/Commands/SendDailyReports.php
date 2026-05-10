<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use App\Services\PHPMailerService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class SendDailyReports extends Command
{
    protected $signature = 'app:send-daily-reports';
    protected $description = 'Genera reportes automáticos enviados por correo a Administradores y Doctores';

    public function handle()
    {
        $today = Carbon::today()->toDateString();
        $phpMailer = new PHPMailerService();
        
        // 1. Obtener todas las citas de hoy (Status 1 = Agendada/Programada)
        $allAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $today)
            ->where('status', 1) 
            ->orderBy('start_time')
            ->get();

        if ($allAppointments->isEmpty()) {
            $this->info("No hay citas programadas para hoy ($today). No se enviarán reportes.");
            return;
        }

        // 2. REPORTE PARA LOS ADMINISTRADORES
        $admins = User::role('Administrador')->get();
        
        if ($admins->isNotEmpty()) {
            $this->info("Generando reporte para " . $admins->count() . " administradores...");
            $adminBody = View::make('emails.daily-admin-report', ['appointments' => $allAppointments])->render();
            
            foreach ($admins as $admin) {
                $phpMailer->sendWithPDF(
                    $admin->email,
                    "REPORTES: Agenda General del Hospital - " . date('d/m/Y'),
                    $adminBody
                );
                $this->line("Reporte enviado a: {$admin->email}");
            }
        }

        // 3. REPORTES PARA CADA DOCTOR
        $doctors = Doctor::with('user')->get();

        foreach ($doctors as $doctor) {
            $doctorAppointments = $allAppointments->where('doctor_id', $doctor->id);
            
            if ($doctorAppointments->count() > 0) {
                $this->info("Enviando agenda al Dr. {$doctor->user->name}...");
                
                $doctorBody = View::make('emails.daily-doctor-report', [
                    'doctorName' => $doctor->user->name,
                    'appointments' => $doctorAppointments
                ])->render();

                $phpMailer->sendWithPDF(
                    $doctor->user->email,
                    "TU AGENDA: Lista de pacientes para hoy - " . date('d/m/Y'),
                    $doctorBody
                );
            }
        }

        $this->info("¡Todos los reportes diarios han sido enviados correctamente!");
    }
}
