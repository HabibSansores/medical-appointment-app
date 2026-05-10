<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Enviar recordatorios de WhatsApp y Correo exactamente 24 horas antes de la cita (se revisa cada minuto)
Schedule::command('app:send-appointment-reminders')->everyMinute();

// Reportes automáticos todos los días a las 08:00 AM
Schedule::command('app:send-daily-reports')->dailyAt('08:00');