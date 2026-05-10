<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $url;
    protected $token;
    protected $instanceId;

    public function __construct()
    {
        $this->instanceId = config('services.whatsapp.instance_id');
        $this->token = config('services.whatsapp.token');
        $this->url = "https://api.ultramsg.com/{$this->instanceId}/messages/chat";
    }

    public function sendMessage($to, $message)
    {
        $to = preg_replace('/[^0-9]/', '', $to);

        if (empty($this->token) || empty($this->instanceId)) {
            Log::info("UltraMsg Simulado a {$to}: {$message}");
            return true;
        }

        try {
            $response = Http::post($this->url, [
                'token' => $this->token,
                'to' => $to,
                'body' => $message,
                'priority' => 10,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp (UltraMsg) enviado exitosamente a {$to}");
                return true;
            }

            Log::error("Error UltraMsg API a {$to}: " . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error("Excepción UltraMsg API: " . $e->getMessage());
            return false;
        }
    }

    /**
     * MENSAJE DE CONFIRMACIÓN (Al momento de crear la cita)
     */
    public function sendAppointmentConfirmation($to, $patientName, $doctorName, $date, $time)
    {
        $message = "✅ *CITA CONFIRMADA*\n\n" .
                   "Hola *{$patientName}*, tu cita médica en *Hospital Healthify* ha sido agendada con éxito:\n\n" .
                   "👨‍⚕️ *Doctor:* {$doctorName}\n" .
                   "📅 *Fecha:* {$date}\n" .
                   "⏰ *Hora:* {$time}\n\n" .
                   "📧 También te hemos enviado un comprobante a tu correo electrónico con los detalles y un PDF adjunto.\n\n" .
                   "¡Gracias por confiar en nosotros!";
        
        return $this->sendMessage($to, $message);
    }

    /**
     * MENSAJE DE RECORDATORIO (24 horas antes)
     */
    public function sendAppointmentReminder($to, $patientName, $doctorName, $date, $time)
    {
        $message = "⏰ *RECORDATORIO DE CITA*\n\n" .
                   "Hola *{$patientName}*, te recordamos que tienes una consulta programada para *MAÑANA*:\n\n" .
                   "👨‍⚕️ *Doctor:* {$doctorName}\n" .
                   "📅 *Fecha:* {$date}\n" .
                   "⏰ *Hora:* {$time}\n\n" .
                   "📍 Por favor, recuerda llegar 15 minutos antes de tu cita.\n" .
                   "¡Te esperamos en *Hospital Healthify*!";
        
        return $this->sendMessage($to, $message);
    }
}
