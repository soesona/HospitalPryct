@extends('adminlte::page')

@section('title', 'Pacientes')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />


@stop

@section('content_header')
    <h1><span class="font-weight-bold">Listado de Pacientes</span></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('pacientes.pdf') }}" class="btn btn-secondary">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
        </div>

        <div class="card-body table-responsive">
        <table id="pagination-table" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código Paciente</th>
                        <th>Código Usuario</th>
                        <th>Nombre Completo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaPacientes as $paciente)
                        <tr>
                            <td>{{ $paciente->codigoPaciente }}</td>
                            <td>{{ $paciente->codigoUsuario }}</td>
                           <td>{{ ucwords(strtolower($paciente->usuario->nombreCompleto)) }}</td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

   
    

@stop

@section('js')

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@vite('resources/js/app.js') 
@stop
