<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', sans-serif; color: #2d3748; margin: 0; padding: 0; }
        .header-bar { background-color: #3182ce; height: 10px; }
        .container { padding: 50px; }
        .brand { font-size: 32px; font-weight: bold; color: #2b6cb0; margin-bottom: 5px; }
        .subtitle { font-size: 14px; color: #718096; text-transform: uppercase; letter-spacing: 1px; }
        .receipt-title { font-size: 24px; font-weight: bold; margin-top: 50px; border-bottom: 2px solid #edf2f7; padding-bottom: 10px; }
        .grid { width: 100%; margin-top: 30px; }
        .grid td { padding: 15px 0; border-bottom: 1px solid #f7fafc; }
        .label { font-weight: bold; color: #4a5568; width: 30%; }
        .value { color: #2d3748; }
        .info-box { margin-top: 40px; background-color: #f7fafc; padding: 20px; border-radius: 8px; }
        .info-box p { margin: 5px 0; font-size: 14px; color: #4a5568; }
        .footer { position: absolute; bottom: 50px; left: 50px; right: 50px; text-align: center; font-size: 11px; color: #a0aec0; border-top: 1px solid #edf2f7; padding-top: 20px; }
        .qr-placeholder { float: right; width: 100px; height: 100px; background-color: #edf2f7; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="header-bar"></div>
    <div class="container">
        <div style="overflow: hidden;">
            <div style="float: left;">
                <div class="brand">HEALTHIFY</div>
                <div class="subtitle">SISTEMA DE GESTIÓN HOSPITALARIA</div>
            </div>
            <div style="float: right; text-align: right;">
                <p style="margin: 0; font-weight: bold;">Comprobante de Cita</p>
                <p style="margin: 0; color: #718096;">Ref: #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <h1 class="receipt-title">Detalles de la Programación</h1>

        <table class="grid">
            <tr>
                <td class="label">Paciente</td>
                <td class="value">{{ $appointment->patient->user->name }}</td>
            </tr>
            <tr>
                <td class="label">Identificación</td>
                <td class="value">{{ $appointment->patient->user->id_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Especialista</td>
                <td class="value">Dr. {{ $appointment->doctor->user->name }}</td>
            </tr>
            <tr>
                <td class="label">Especialidad</td>
                <td class="value">{{ $appointment->doctor->specialty }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Cita</td>
                <td class="value">{{ \Carbon\Carbon::parse($appointment->date)->format('l, d de F de Y') }}</td>
            </tr>
            <tr>
                <td class="label">Hora Programada</td>
                <td class="value">{{ $appointment->start_time }}</td>
            </tr>
            <tr>
                <td class="label">Motivo</td>
                <td class="value">{{ $appointment->reason }}</td>
            </tr>
        </table>

        <div class="info-box">
            <p><strong>Instrucciones Importantes:</strong></p>
            <p>1. Presentarse 15 minutos antes de la hora programada.</p>
            <p>2. Llevar identificación oficial vigente.</p>
            <p>3. En caso de cancelación, avisar con 24 horas de antelación.</p>
        </div>

        <div class="footer">
            <p>Este documento es un comprobante digital emitido por Hospital Healthify. No requiere firma física.</p>
            <p>Av. Salud 456, Ciudad Médica | Tel: (55) 1234-5678 | www.healthify-hospital.com</p>
        </div>
    </div>
</body>
</html>
