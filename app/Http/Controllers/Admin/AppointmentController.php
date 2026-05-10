<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use App\Services\PHPMailerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = \App\Models\Appointment::with(['patient.user', 'doctor.user'])->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = \App\Models\Patient::with('user')->get();
        $doctors = \App\Models\Doctor::with('user')->get();
        $specialties = \App\Models\Doctor::whereNotNull('specialty')->distinct()->pluck('specialty');
        return view('admin.appointments.create', compact('patients', 'doctors', 'specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->date === \Carbon\Carbon::today()->toDateString()) {
                        if ($value < \Carbon\Carbon::now()->format('H:i')) {
                            $fail('La hora de inicio no puede ser en el pasado para el día de hoy.');
                        }
                    }
                },
            ],
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $wordCount = str_word_count(trim($value));
                    if ($wordCount < 5) {
                        $fail('El motivo de la cita debe contener al menos 5 palabras.');
                    }
                },
            ]
        ]);

        // 1. Verificar si el doctor ya tiene una cita en ese rango de tiempo (mismo día)
        $doctorConflict = Appointment::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($doctorConflict) {
            return back()->withErrors(['doctor_id' => 'El doctor seleccionado ya tiene una cita programada en este horario.'])
                         ->withInput();
        }

        // 2. Verificar si el paciente ya tiene otra cita en ese mismo rango (mismo día)
        $patientConflict = Appointment::where('patient_id', $request->patient_id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($patientConflict) {
            return back()->withErrors(['patient_id' => 'El paciente ya tiene una cita agendada en este mismo horario.'])
                         ->withInput();
        }

        $appointment = Appointment::create($request->all());

        // 1. Generar el PDF en memoria usando DomPDF
        $pdf = Pdf::loadView('emails.appointment-receipt-pdf', compact('appointment'));
        $pdfContent = $pdf->output();

        $phpMailer = new PHPMailerService();

        // 2. CORREO AL PACIENTE: Confirmación con PDF adjunto
        $patientEmailBody = View::make('emails.appointment-receipt-body', compact('appointment'))->render();
        $phpMailer->sendWithPDF(
            $appointment->patient->user->email,
            'Confirmación de Cita Médica - Healthify',
            $patientEmailBody,
            $pdfContent,
            'comprobante_cita.pdf'
        );

        // 3. CORREO AL DOCTOR: Notificación con datos del paciente (nombre, hora, motivo)
        $doctorEmailBody = View::make('emails.appointment-doctor-notification', compact('appointment'))->render();
        $phpMailer->sendWithPDF(
            $appointment->doctor->user->email,
            'Nueva Cita Programada - Healthify',
            $doctorEmailBody
        );

        // 4. Enviar WhatsApp al Paciente con los datos reales usando la plantilla personalizada
        $whatsAppService = new WhatsAppService();
        $whatsAppService->sendAppointmentConfirmation(
            $appointment->patient->user->phone,
            $appointment->patient->user->name,
            $appointment->doctor->user->name,
            \Carbon\Carbon::parse($appointment->date)->format('d/m/Y'),
            $appointment->start_time
        );

        return redirect()->route('admin.appointments.index')
                         ->with('success', 'Cita creada exitosamente y notificaciones enviadas.');
    }

    public function consultation(\App\Models\Appointment $appointment)
    {
        return view('admin.appointments.consultation', compact('appointment'));
    }
}
