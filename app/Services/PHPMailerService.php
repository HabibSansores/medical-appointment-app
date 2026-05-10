<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class PHPMailerService
{
    public function sendWithPDF($to, $subject, $body, $pdfContent = '', $pdfName = '', $cc = null)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = config('mail.mailers.smtp.username');
            $mail->Password   = config('mail.mailers.smtp.password');
            
            // Forzamos SSL en puerto 465
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            // Bypass de certificados (Común en Windows/Localhost)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Remitente y Destinatario
            $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mail->addAddress($to);
            
            if ($cc) {
                $mail->addCC($cc);
            }

            // Adjunto (Solo si hay contenido)
            if (!empty($pdfContent)) {
                $mail->addStringAttachment($pdfContent, $pdfName);
            }

            // Contenido
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error("Error de PHPMailer: {$mail->ErrorInfo}");
            return false;
        }
    }
}
