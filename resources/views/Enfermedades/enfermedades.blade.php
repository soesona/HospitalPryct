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
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearEnfermedad">
                <i class="fas fa-plus"></i> Registrar Nueva Enfermedad
            </button>
        </div>

        <div class="card-body">
            <table id="pagination-table" class="table table-bordered table-striped">
                <thead class="bg-success text-white">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($enfermedades as $enfermedad)
                        <tr>
                            <td>{{ $enfermedad->codigoEnfermedad }}</td>
                            <td>{{ ucfirst(strtolower($enfermedad->nombre)) }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditarEnfermedad"
                                    data-toggle="modal" data-target="#modalEditarEnfermedad"
                                    data-id="{{ $enfermedad->codigoEnfermedad }}"
                                    data-nombre="{{ ucfirst(strtolower($enfermedad->nombre)) }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCrearEnfermedad" tabindex="-1" role="dialog" aria-labelledby="modalCrearEnfermedadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('enfermedad.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCrearEnfermedadLabel">Registrar Nueva Enfermedad</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreCrear">Nombre</label>
                        <input type="text" name="nombre" id="nombreCrear" class="form-control" value="{{ old('nombre') }}" required>
                         @error('nombre')
                         <small class="text-danger">{{ $message }}</small>
                         @enderror
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
    <div class="modal fade" id="modalEditarEnfermedad" tabindex="-1" role="dialog" aria-labelledby="modalEditarEnfermedadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="formEditarEnfermedad" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalEditarEnfermedadLabel">Editar Enfermedad</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="codigoEnfermedad" id="codigoEnfermedadEditar">
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
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@vite('resources/js/app.js')

<script>
   
    $('.btnEditarEnfermedad').on('click', function () {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');

        $('#codigoEnfermedadEditar').val(id);
        $('#nombreEditar').val(nombre);

        $('#formEditarEnfermedad').attr('action', '/enfermedad/' + id);
    });


     @if ($errors->any())
        $('#modalCrearEnfermedad').modal('show');
    @endif
</script>
@stop
