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
        h1 {
            text-align: center;
            margin-bottom: 20px;
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

    <h1>Reporte de Especialidades</h1>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($especialidades as $especialidad)
                <tr>
                    <td>{{ $especialidad->codigoEspecialidad }}</td>
                    <td>{{ ucfirst(strtolower($especialidad->nombre)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>