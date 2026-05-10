<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; }
        .container { max-width: 700px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; }
        .header { background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #64748b; font-size: 12px; text-transform: uppercase; padding: 12px; border-bottom: 2px solid #e2e8f0; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hospital Healthify</h1>
            <p>Tu Agenda del Día - {{ date('d/m/Y') }}</p>
        </div>
        <div class="content">
            <p>Buen día, <strong>Dr. {{ $doctorName }}</strong>. Estos son sus pacientes programados para hoy:</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Teléfono</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                        <tr>
                            <td><strong>{{ $appointment->start_time }}</strong></td>
                            <td>{{ $appointment->patient->user->name }}</td>
                            <td>{{ $appointment->patient->user->phone }}</td>
                            <td>{{ $appointment->reason }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="footer">
            <p>Por favor, asegúrese de registrar los diagnósticos en el sistema al terminar cada consulta.</p>
        </div>
    </div>
</body>
</html>
