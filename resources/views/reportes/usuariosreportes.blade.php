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
        ul {
            padding-left: 15px;
            margin: 0;
        }
    </style>
</head>
<body>

   <div class="fecha">
         Fecha del reporte: {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    </div>


        <h1>Reporte de Usuarios</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Rol(es)</th>
                <th>Creado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->codigoUsuario }}</td>
                    <td>{{ ucwords(strtolower($usuario->nombreCompleto)) }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->getRoleNames()->implode(', ') }}</td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
