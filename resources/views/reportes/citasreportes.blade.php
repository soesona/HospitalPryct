<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    @if($estado)
    <p><strong>Filtro aplicado:</strong> Citas {{ ucfirst($estado) }}</p>
@endif
    <title>Reporte de Citas</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
            padding: 20px;
        }
        .fecha {
            text-align: right;
            font-size: 12px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="fecha">
        Fecha del reporte: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    </div>

    <h1>Reporte de Citas</h1>
    <table>
        <thead>
            <tr>
                <th>Identidad</th>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Especialidad</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($citas as $cita)
                <tr>
                    <td>{{ $cita->paciente->usuario->identidad ?? 'N/A' }}</td>
                    <td>{{ ucwords(strtolower($cita->paciente->usuario->nombreCompleto ?? 'N/A')) }}</td>
                    <td>{{ ucwords(strtolower($cita->doctor->user->nombreCompleto ?? 'N/A')) }}</td>
                    <td>{{ ucfirst(strtolower($cita->doctor->especialidad->nombre ?? 'No definida')) }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->fechaCita)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($cita->horaInicio)->format('H:i') }}</td>
                    <td>{{ ucfirst($cita->estado) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
