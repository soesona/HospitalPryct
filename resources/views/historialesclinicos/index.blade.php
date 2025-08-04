@extends('adminlte::page')

@section('title', 'Historiales Clínicos')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
@stop

@section('content_header')
    <h1>Historiales Clínicos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="pagination-table" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Paciente</th>
                        <th>Doctor</th>
                        <th>Consulta</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historiales as $index => $historial)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucwords(strtolower($historial->paciente->usuario->nombreCompleto ?? 'No encontrado') ) }}</td>
                            <td>{{ ucwords(strtolower($historial->consulta->doctor->user->nombreCompleto ?? 'Sin doctor')) }}</td></td>
                            <td>
                                {{ $historial->consulta->codigoConsulta ?? 'Consulta sin enfermedad' }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($historial->fechaRegistro)->format('d/m/Y') }}</td>
                            <td>{{ ucwords(strtolower($historial->descripcion))  }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                {{ $historiales->links() }}
            </div>
        </div>
    </div>
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    @vite('resources/js/app.js')
@stop
