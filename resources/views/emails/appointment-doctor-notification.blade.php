<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f0f4f8; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f0f4f8; padding-bottom: 40px; padding-top: 40px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #1a202c; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .header { background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 40px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 700; letter-spacing: -0.025em; }
        .header p { margin: 8px 0 0; font-size: 14px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .content h2 { color: #2d3748; font-size: 20px; margin-top: 0; }
        .info-card { background-color: #f0fdf4; border-radius: 12px; padding: 25px; margin: 25px 0; border-left: 6px solid #059669; }
        .info-row { display: table; width: 100%; margin-bottom: 10px; }
        .info-label { display: table-cell; width: 40%; font-weight: 600; color: #4a5568; font-size: 14px; }
        .info-value { display: table-cell; width: 60%; color: #2d3748; font-size: 15px; font-weight: 500; }
        .btn-container { text-align: center; margin-top: 30px; }
        .btn { background-color: #059669; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block; }
        .footer { text-align: center; padding: 30px; font-size: 12px; color: #718096; }
        .badge { display: inline-block; background-color: #ecfdf5; color: #047857; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <h1>Nueva Cita Programada</h1>
                    <p>Se ha agendado un nuevo paciente en su consultorio</p>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <div class="badge">Nueva Cita</div>
                    <h2>Estimado Dr. {{ $appointment->doctor->user->name }},</h2>
                    <p>Le informamos que se ha programado una nueva consulta en su agenda. A continuación, los detalles del paciente:</p>

                    <div class="info-card">
                        <div class="info-row">
                            <div class="info-label">Paciente</div>
                            <div class="info-value">{{ $appointment->patient->user->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Fecha</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Horario</div>
                            <div class="info-value">{{ $appointment->start_time }} - {{ $appointment->end_time }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Motivo de consulta</div>
                            <div class="info-value">{{ $appointment->reason }}</div>
                        </div>
                    </div>

                    <p>Por favor, revise su agenda y prepare lo necesario para la consulta.</p>

                    <div class="btn-container">
                        <a href="{{ config('app.url') }}" class="btn">Ver mi Agenda</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p><strong>Hospital Healthify</strong><br>Excelencia en el cuidado de tu salud.</p>
                    <p>Este es un correo automático generado por el sistema de gestión de citas.</p>
                    <p style="margin-top: 20px;">&copy; {{ date('Y') }} Healthify. Av. Salud #456, Ciudad Médica.</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
