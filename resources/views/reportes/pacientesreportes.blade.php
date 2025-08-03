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

    <h1>Reporte de Pacientes</h1>

    <table>
        <thead>
            <tr>
                <th>Código Paciente</th>
                <th>Código Usuario</th>
                <th>Nombre Completo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pacientes as $paciente)
                <tr>
                    <td>{{ $paciente->codigoPaciente }}</td>
                    <td>{{ $paciente->codigoUsuario }}</td>
                    <td>{{ ucwords(strtolower($paciente->usuario->nombreCompleto ?? 'Sin datos')) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
