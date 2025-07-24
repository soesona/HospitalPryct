@extends('adminlte::page')

@section('title', 'Enfermedades')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
@stop

@section('content_header')
    <h1>Listado de Enfermedades</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="" class="btn btn-primary">
                <i class="fas fa-plus"></i> Registrar Enfermedad
            </a>
        </div>
        <div class="card-body table-responsive">
            <table id="pagination-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enfermedades as $enfermedad)
                        <tr>
                            <td>{{ $enfermedad->codigoEnfermedad }}</td>
                            <td>{{ $enfermedad->nombre }}</td>
                            <td>
                                <a href="" class="btn btn-sm btn-warning">
                                    Editar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
@vite('resources/js/app.js')
@stop