<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f0f4f8; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f0f4f8; padding-bottom: 40px; padding-top: 40px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #1a202c; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .header { background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%); padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 700; letter-spacing: -0.025em; }
        .content { padding: 40px 30px; }
        .content h2 { color: #2d3748; font-size: 20px; margin-top: 0; }
        .info-card { background-color: #edf2f7; border-radius: 12px; padding: 25px; margin: 25px 0; border-left: 6px solid #3182ce; }
        .info-row { display: table; width: 100%; margin-bottom: 10px; }
        .info-label { display: table-cell; width: 40%; font-weight: 600; color: #4a5568; font-size: 14px; }
        .info-value { display: table-cell; width: 60%; color: #2d3748; font-size: 15px; font-weight: 500; }
        .btn-container { text-align: center; margin-top: 30px; }
        .btn { background-color: #3182ce; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block; transition: background-color 0.2s; }
        .footer { text-align: center; padding: 30px; font-size: 12px; color: #718096; }
        .badge { display: inline-block; background-color: #ebf8ff; color: #2b6cb0; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <img src="https://via.placeholder.com/150x50/ffffff/3182ce?text=HEALTHIFY" alt="Logo" style="margin-bottom: 15px;">
                    <h1>Confirmación de Cita Médica</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <div class="badge">Confirmada</div>
                    <h2>Hola {{ $appointment->patient->user->name }},</h2>
                    <p>Nos complace informarte que tu cita ha sido programada con éxito en nuestro sistema. A continuación, encontrarás los detalles clave:</p>
                    
                    <div class="info-card">
                        <div class="info-row">
                            <div class="info-label">Doctor</div>
                            <div class="info-value">Dr. {{ $appointment->doctor->user->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Especialidad</div>
                            <div class="info-value">{{ $appointment->doctor->specialty }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Fecha</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Horario</div>
                            <div class="info-value">{{ $appointment->start_time }}</div>
                        </div>
                    </div>

                    <p>Adjunto a este correo encontrarás el **Comprobante Oficial (PDF)**. Por favor, llévalo contigo (digital o impreso) el día de tu consulta.</p>
                    
                    <div class="btn-container">
                        <a href="{{ config('app.url') }}" class="btn">Acceder a mi Portal</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p><strong>Hospital Healthify</strong><br>Excelencia en el cuidado de tu salud.</p>
                    <p>Si tienes alguna duda, contáctanos al (55) 1234-5678 o responde a este correo.</p>
                    <p style="margin-top: 20px;">&copy; {{ date('Y') }} Healthify. Av. Salud #456, Ciudad Médica.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
