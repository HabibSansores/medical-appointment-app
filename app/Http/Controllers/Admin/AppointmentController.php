<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        \App\Models\Appointment::create($request->all());

        return redirect()->route('admin.appointments.index')
                         ->with('success', 'Cita creada exitosamente.');
    }

    public function consultation(\App\Models\Appointment $appointment)
    {
        return view('admin.appointments.consultation', compact('appointment'));
    }
}
