<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #64748b; font-size: 12px; text-transform: uppercase; padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 30px; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .bg-blue { background-color: #dbeafe; color: #1e40af; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hospital Healthify</h1>
            <p>Reporte General de Citas - {{ date('d/m/Y') }}</p>
        </div>
        <div class="content">
            <p>Buen día, Administrador. A continuación se detalla la agenda general del hospital para el día de hoy:</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                        <tr>
                            <td><strong>{{ $appointment->start_time }}</strong></td>
                            <td>{{ $appointment->patient->user->name }}</td>
                            <td>{{ $appointment->doctor->user->name }}</td>
                            <td>{{ $appointment->reason }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94a3b8;">No hay citas programadas para hoy.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Este es un reporte automático generado por el sistema de gestión Healthify.</p>
        </div>
    </div>
</body>
</html>
