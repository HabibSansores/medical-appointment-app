<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorScheduleController;
use App\Http\Controllers\Admin\DoctorController;

Route:: get('/', function () {
    return view ('admin.dashboard');
})->name('dashboard');

// Gestion de roles
Route::resource('roles',RoleController::class);

// Gestion de Usuarios
Route::resource('users',UserController::class);

// Gestion de Doctores
Route::resource('doctors', DoctorController::class);
Route::get('doctors/{doctor}/schedule', [DoctorScheduleController::class, 'edit'])->name('doctors.schedule');
Route::put('doctors/{doctor}/schedule', [DoctorScheduleController::class, 'update'])->name('doctors.schedule.update');

// Gestion de Pacientes
Route::resource('patients',PatientController::class);

// Gestion de Citas
Route::get('appointments/{appointment}/consultation', [App\Http\Controllers\Admin\AppointmentController::class, 'consultation'])->name('appointments.consultation');
Route::resource('appointments',App\Http\Controllers\Admin\AppointmentController::class);
