<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use App\Services\PHPMailerService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class SendAppointmentReminders extends Command
{
    protected $signature = 'app:send-appointment-reminders';
    protected $description = 'Envía recordatorios de Correo y WhatsApp exactamente 24 horas antes de la cita, evitando duplicados';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $currentTime = Carbon::now()->format('H:i');

        // Obtener citas de mañana cuya hora de inicio ya es alcanzada (exactamente 24 horas antes o un poco después si hubo retraso)
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $tomorrow)
            ->where('start_time', '<=', $currentTime)
            ->where('reminder_sent', false)
            ->where('status', 1)
            ->orderBy('start_time')
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No hay citas pendientes de recordatorio para mañana ($tomorrow).");
            return;
        }

        $this->info("Se encontraron {$appointments->count()} citas para mañana que necesitan recordatorio.");

        $whatsAppService = new WhatsAppService();
        $phpMailer = new PHPMailerService();

        foreach ($appointments as $appointment) {
            $this->info("Procesando recordatorio para cita #{$appointment->id} de {$appointment->patient->user->name}");

            // 1. WhatsApp al Paciente
            $whatsAppService->sendAppointmentReminder(
                $appointment->patient->user->phone,
                $appointment->patient->user->name,
                $appointment->doctor->user->name,
                Carbon::parse($appointment->date)->format('d/m/Y'),
                $appointment->start_time
            );

            // 2. Correo al Paciente con PDF adjunto
            $pdf = Pdf::loadView('emails.appointment-receipt-pdf', compact('appointment'));
            $pdfContent = $pdf->output();
            $patientBody = View::make('emails.appointment-reminder-patient', compact('appointment'))->render();

            $phpMailer->sendWithPDF(
                $appointment->patient->user->email,
                'RECORDATORIO: Tu cita médica es mañana - Healthify',
                $patientBody,
                $pdfContent,
                'recordatorio_cita.pdf'
            );

            // 3. Correo al Doctor con info del paciente
            $doctorBody = View::make('emails.appointment-reminder-doctor', compact('appointment'))->render();
            $phpMailer->sendWithPDF(
                $appointment->doctor->user->email,
                'RECORDATORIO: Paciente agendado para mañana - Healthify',
                $doctorBody
            );

            // 4. Marcar como enviado para no duplicar
            $appointment->update(['reminder_sent' => true]);
            $this->line("✅ Recordatorio enviado para cita #{$appointment->id}");
        }

        $this->info("¡Todos los recordatorios han sido enviados correctamente!");
    }
}

