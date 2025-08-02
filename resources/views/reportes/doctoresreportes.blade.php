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

    <h1>Reporte de Doctores</h1>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Especialidad</th>
                <th>Horarios</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctores as $doctor)
            <tr>
                <td>
                    @if($doctor->doctor)
                        {{ $doctor->doctor->codigoDoctor }}
                    @else
                        No aplica
                    @endif
                </td>
                <td>{{ ucwords(strtolower($doctor->nombreCompleto)) }}</td>
                <td>{{ $doctor->email }}</td>
                <td>{{ $doctor->telefono }}</td>
                <td>
                    @if($doctor->doctor)
                        Con registro
                    @else
                        Sin registro
                    @endif
                </td>
                <td>
                    @if($doctor->doctor && $doctor->doctor->especialidad)
                        {{ ucfirst(strtolower($doctor->doctor->especialidad->nombre)) }}
                    @else
                        No aplica
                    @endif
                </td>
                <td>
                    @if($doctor->doctor && $doctor->doctor->horarios->count())
                        <ul>
                            @foreach($doctor->doctor->horarios as $horario)
                                <li>
                                    {{ ucfirst($horario->diaSemana) }}: 
                                    {{ \Carbon\Carbon::parse($horario->horaInicio)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($horario->horaFin)->format('g:i A') }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        No aplica
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
