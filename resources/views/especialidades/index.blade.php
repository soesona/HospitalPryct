@extends('adminlte::page')

@section('title', 'Especialidades')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
@stop

@section('content_header')
    <h1>Especialidades</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearEspecialidad">
                <i class="fas fa-plus"></i> Registrar Nueva Especialidad
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead class="bg-success text-white">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($especialidades as $especialidad)
                        <tr>
                            <td>{{ $especialidad->codigoEspecialidad }}</td>
                            <td>{{ ucfirst(strtolower($especialidad->nombre)) }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditarEspecialidad"
                                    data-toggle="modal" data-target="#modalEditarEspecialidad"
                                    data-id="{{ $especialidad->codigoEspecialidad }}"
                                    data-nombre="{{ ucfirst(strtolower($especialidad->nombre)) }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="modal fade" id="modalCrearEspecialidad" tabindex="-1" role="dialog" aria-labelledby="modalCrearEspecialidadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('especialidades.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCrearEspecialidadLabel">Registrar Nueva Especialidad</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreCrear">Nombre</label>
                        <input type="text" name="nombre" id="nombreCrear" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Registrar</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditarEspecialidad" tabindex="-1" role="dialog" aria-labelledby="modalEditarEspecialidadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="formEditarEspecialidad" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditarEspecialidadLabel">Editar Especialidad</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="codigoEspecialidad" id="codigoEspecialidadEditar">
                    <div class="form-group">
                        <label for="nombreEditar">Nombre</label>
                        <input type="text" name="nombre" id="nombreEditar" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar Cambios</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    // Al abrir modal editar, cargamos los datos en el formulario
    $('.btnEditarEspecialidad').on('click', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');

        $('#codigoEspecialidadEditar').val(id);
        $('#nombreEditar').val(nombre);

        // Actualizamos la acción del form para que apunte a la ruta update con el ID
        $('#formEditarEspecialidad').attr('action', '/especialidades/' + id);
    });
</script>
@stop