<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ConsultationManager extends Component
{
    public \App\Models\Appointment $appointment;

    public $diagnosis;
    public $treatment;
    public $notes;
    
    public $medications = [];
    public $newMedication = ['name' => '', 'dosage' => '', 'frequency' => ''];
    
    public $previousConsultations = [];

    public function mount(\App\Models\Appointment $appointment)
    {
        $this->appointment = $appointment->load(['patient.user', 'doctor.user']);
        
        // Cargar consultas anteriores completadas del paciente
        $this->previousConsultations = \App\Models\Appointment::with(['doctor.user'])
            ->where('patient_id', $this->appointment->patient_id)
            ->where('id', '!=', $this->appointment->id)
            ->where('status', 2) // Completado
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
    }

    public function addMedication()
    {
        $this->validate([
            'newMedication.name' => 'required|string',
            'newMedication.dosage' => 'required|string',
            'newMedication.frequency' => 'required|string',
        ]);

        $this->medications[] = $this->newMedication;
        $this->newMedication = ['name' => '', 'dosage' => '', 'frequency' => ''];
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
    }

    public function saveConsultation()
    {
        $this->validate([
            'diagnosis' => 'required|string|min:5',
            'treatment' => 'required|string|min:5',
            'notes' => 'nullable|string',
            'medications' => 'required|array|min:1'
        ], [
            'diagnosis.required' => 'El campo de diagnóstico es obligatorio.',
            'diagnosis.min' => 'El diagnóstico debe tener al menos 5 caracteres.',
            'treatment.required' => 'El campo de tratamiento es obligatorio.',
            'treatment.min' => 'El tratamiento debe tener al menos 5 caracteres.',
            'medications.required' => 'Debe agregar al menos un medicamento a la receta.',
            'medications.min' => 'Debe agregar al menos un medicamento a la receta.'
        ]);

        $this->appointment->update([
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'prescription' => $this->medications,
            'status' => 2 // Completado
        ]);
        
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Consulta Guardada!',
            'text' => 'Los datos de la consulta se han guardado exitosamente.'
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}
