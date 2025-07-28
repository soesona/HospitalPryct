@extends('adminlte::page')

@section('title', 'Pacientes')

@section('content_header')
    <h1 class="mb-3">Listado de Pacientes</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrear">
                <i class="fas fa-plus"></i> Registrar Nuevo Paciente
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código Paciente</th>
                        <th>Código Usuario</th>
                        <th>Nombre Completo</th>
                        <th>Editar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaPacientes as $paciente)
                        <tr>
                            <td>{{ $paciente->codigoPaciente }}</td>
                            <td>{{ $paciente->codigoUsuario }}</td>
                            <td>{{ $paciente->usuario->nombreCompleto}}</td>
                            <td>
                                <button class="btn btn-warning btn-sm ejecutar"
                                    data-toggle="modal" data-target="#mEditarPaciente"
                                    data-codigoP="{{ $paciente->codigoPaciente }}"
                                    data-codigoU="{{ $paciente->codigoUsuario }}">
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
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <form action="/pacientes" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Pacientes</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Codigo Paciente</label>
                        <input type="text" name="codigoPaciente" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Codigo Usuario</label>
                        <input type="text" name="codigoUsuario" class="form-control">
                    </div>
                <div class="modal-footer">
                    <button class="btn btn-success">Registrar</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="mEditarMedicamento" tabindex="-1">
        <div class="modal-dialog">
            <form action="/pacientes" method="POST" class="modal-content" id="miFormU">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Editar Paciente</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Código Paciente</label>
                        <input type="text" id="codigoPacienteu" name="codigoPacienteu" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Codigo Usuario</label>
                        <input type="text" id="codigoUsuariou" name="codigoUsuariou" class="form-control">
                    </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    

@stop

@section('js')
<script>
    document.querySelectorAll('.ejecutar').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('codigoPacienteu').value = this.dataset.codigoP;
            document.getElementById('codigoUsuariou').value = this.dataset.codigoU;
        });
    });
</script>

@stop
