<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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

    <h1>Reporte de Consultas</h1>
    <p><strong>Doctor que genera el reporte:</strong> {{ ucwords(strtolower($nombreDoctor)) }}</p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Cita</th>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Enfermedad</th>
                <th>Diagnóstico</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($consultas as $consulta)
            <tr>
                <td>{{ $consulta->codigoConsulta }}</td>
                <td>{{ $consulta->codigoCita }}</td>
                <td>{{ ucfirst(strtolower($consulta->paciente->usuario->nombreCompleto ?? 'Desconocido')) }}</td>
                <td>{{ ucfirst(strtolower($consulta->doctor->user->nombreCompleto ?? 'Desconocido')) }}</td>
                <td>{{ ucfirst(strtolower($consulta->enfermedad->nombre ?? 'Desconocida')) }}</td>
                <td>{{ ucfirst($consulta->diagnostico) }}</td>
                <td>{{ ucfirst($consulta->observaciones) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
