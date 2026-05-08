<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorScheduleController extends Controller
{
    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.schedule', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'schedule' => 'nullable|array',
        ]);

        $doctor->update([
            'schedule' => $request->schedule ?? []
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Horario actualizado',
            'text' => 'El horario del doctor ha sido actualizado correctamente.',
        ]);

        return redirect()->route('admin.doctors.index');
    }
}
