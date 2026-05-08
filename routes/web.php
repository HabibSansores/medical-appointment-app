<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard'); // Ajustado para que el dashboard apunte a admin.dashboard

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class);
        Route::resource('doctors', \App\Http\Controllers\Admin\DoctorController::class);
        Route::get('appointments/{appointment}/consultation', \App\Livewire\Admin\ConsultationManager::class)
            ->name('consultations.show');
    });
});
