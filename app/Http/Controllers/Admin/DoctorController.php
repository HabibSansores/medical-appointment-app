<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user')->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'id_number' => 'required|string|unique:users,id_number',
            'phone' => 'required|string',
            'address' => 'required|string|max:500',
            'specialty' => 'required|string',
            'license_number' => 'required|string|unique:doctors,license_number',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'id_number' => $request->id_number,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            $user->assignRole('Doctor');

            Doctor::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty,
                'license_number' => $request->license_number,
            ]);

            DB::commit();

            return redirect()->route('admin.doctors.index')
                             ->with('success', 'Doctor registrado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ocurrió un error al registrar el doctor: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Doctor $doctor)
    {
        try {
            DB::beginTransaction();
            
            $user = $doctor->user;
            $doctor->delete();
            if ($user) {
                $user->delete();
            }

            DB::commit();

            return redirect()->route('admin.doctors.index')
                             ->with('success', 'Doctor eliminado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo eliminar el doctor: ' . $e->getMessage());
        }
    }
}
