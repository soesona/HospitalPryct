@extends('adminlte::page')

@section('title', 'Consultas')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
@stop

@section('content_header')
    <h1>Listado de Consultas</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearConsulta">
            <i class="fas fa-plus"></i> Registrar Consulta
        </a>
    </div>
    <div class="card-body table-responsive">
        <table id="pagination-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Cita</th>
                    <th>Paciente</th>
                    <th>Doctor</th>
                    <th>Enfermedad</th>
                    <th>Diagnóstico</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($consultas as $consulta)
                <tr>
                    <td>{{ $consulta->codigoConsulta }}</td>
                    <td>{{ $consulta->codigoCita }}</td>
                    <td>{{ ucfirst(strtolower($consulta->paciente->usuario->nombreCompleto ?? 'Desconocido')) }}</td>
                    <td>{{ ucfirst(strtolower($consulta->doctor->user->nombreCompleto ?? 'Desconocido')) }}</td>
                    <td>{{ ucfirst(strtolower($consulta->enfermedad->nombre ?? 'Desconocida')) }}</td>
                    <td>{{ ucfirst($consulta->diagnostico) }}</td>
                    <td>{{ ucfirst($consulta->observaciones) }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm btnEditarConsulta"
                            data-toggle="modal" data-target="#modalEditarConsulta"
                            data-id="{{ $consulta->codigoConsulta }}"
                            data-cita="{{ $consulta->codigoCita }}"
                            data-paciente="{{ $consulta->codigoPaciente }}"
                            data-doctor="{{ $consulta->codigoDoctor }}"
                            data-enfermedad="{{ $consulta->codigoEnfermedad }}"
                            data-diagnostico="{{ $consulta->diagnostico }}"
                            data-observaciones="{{ $consulta->observaciones }}">
                            <i class="fas fa-edit"></i> Editar
                        </button>

                        <a href="{{ url('/consultas/' . $consulta->codigoConsulta . '/medicamentos') }}" 
                        class="btn btn-info btn-sm">Asignar Medicamentos</a>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Crear -->
<div class="modal fade" id="modalCrearConsulta" tabindex="-1" role="dialog" aria-labelledby="modalCrearConsultaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('consultas.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalCrearConsultaLabel">Registrar Nueva Consulta</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Campos -->
            <div class="form-group">
                <label for="codigoCitaCrear">Seleccionar Cita</label>
                <select name="codigoCita" id="codigoCitaCrear" class="form-control" required>
                <option value="">-- Seleccione una cita --</option>
                @foreach ($citas as $cita)
                <option 
                    value="{{ $cita->codigoCita }}" 
                    data-paciente="{{ $cita->codigoPaciente }}"
                    data-doctor="{{ $cita->codigoDoctor }}">
                    {{ $cita->codigoCita }} | {{ $cita->fechaCita }} - {{ $cita->paciente->usuario->nombreCompleto ?? 'Sin nombre' }}
                </option>
                @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="codigoPacienteCrear">Paciente</label>
                <input type="number" name="codigoPaciente" id="codigoPacienteCrear" class="form-control" readonly required>
            </div>

            <div class="form-group">
                <label for="codigoDoctorCrear">Código de Doctor</label>
                <input type="number" name="codigoDoctor" id="codigoDoctorCrear" class="form-control" readonly required>
            </div>

               <div class="form-group">
                <label for="codigoEnfermedadCrear">Seleccionar Enfermedad</label>
                <select name="codigoEnfermedad" id="codigoEnfermedadCrear" class="form-control" required>
                <option value="">-- Seleccione una enfermedad --</option>
                 @foreach ($enfermedades as $enfermedad)
                    <option value="{{ $enfermedad->codigoEnfermedad }}">
                        {{$enfermedad->codigoEnfermedad}}: {{ucfirst(strtolower($enfermedad->nombre ))}}</option>
                @endforeach
                </select>
                </div>

                <div class="form-group">
                    <label for="diagnosticoCrear">Diagnóstico</label>
                    <textarea name="diagnostico" id="diagnosticoCrear" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="observacionesCrear">Observaciones</label>
                    <textarea name="observaciones" id="observacionesCrear" class="form-control"></textarea>
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
<div class="modal fade" id="modalEditarConsulta" tabindex="-1" role="dialog" aria-labelledby="modalEditarConsultaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" id="formEditarConsulta" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalEditarConsultaLabel">Editar Consulta</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="codigoConsulta" id="codigoConsultaEditar">
                <div class="form-group">
                    <label for="codigoCitaEditar">Seleccionar Cita</label>
                    <select name="codigoCita" id="codigoCitaEditar" class="form-control" required>
                    <option value="">-- Seleccione una cita --</option>
                    @foreach ($citas as $cita)
                        <option 
                        value="{{ $cita->codigoCita }}"
                        data-paciente="{{ $cita->codigoPaciente }}"
                        data-doctor="{{ $cita->codigoDoctor }}">
                        {{ $cita->codigoCita }} | {{ $cita->fechaCita }} - {{ $cita->paciente->usuario->nombreCompleto ?? 'Sin nombre' }}
                        </option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="codigoPacienteEditar">Paciente</label>
                    <input type="number" name="codigoPaciente" id="codigoPacienteEditar" class="form-control" readonly required>
                </div>

                <div class="form-group">
                    <label for="codigoDoctorEditar">Doctor</label>
                    <input type="number" name="codigoDoctor" id="codigoDoctorEditar" class="form-control" readonly required>
                </div>

                <div class="form-group">
                    <label for="codigoEnfermedadEditar">Seleccionar Enfermedad</label>
                    <select name="codigoEnfermedad" id="codigoEnfermedadEditar" class="form-control" required>
                    <option value="">-- Seleccionar enfermedad --</option>
                    @foreach ($enfermedades as $enfermedad)
                        <option value="{{ $enfermedad->codigoEnfermedad }}">
                            {{$enfermedad->codigoEnfermedad}}: {{ucfirst(strtolower($enfermedad->nombre ))}}</option>
                    @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="diagnosticoEditar">Diagnóstico</label>
                    <textarea name="diagnostico" id="diagnosticoEditar" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="observacionesEditar">Observaciones</label>
                    <textarea name="observaciones" id="observacionesEditar" class="form-control"></textarea>
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

<script>
    $('.btnEditarConsulta').on('click', function () {
        let id = $(this).data('id');

        $('#codigoConsultaEditar').val(id);
        $('#codigoCitaEditar').val($(this).data('cita'));
        $('#codigoPacienteEditar').val($(this).data('paciente'));
        $('#codigoDoctorEditar').val($(this).data('doctor'));
        $('#codigoEnfermedadEditar').val($(this).data('enfermedad'));
        $('#diagnosticoEditar').val($(this).data('diagnostico'));
        $('#observacionesEditar').val($(this).data('observaciones'));

        $('#formEditarConsulta').attr('action', '/consultas/' + id);
    });


    $('#codigoCitaCrear').on('change', function () {
    let selected = $(this).find('option:selected');
    let paciente = selected.data('paciente') || '';
    let doctor = selected.data('doctor') || '';

    $('#codigoPacienteCrear').val(paciente);
    $('#codigoDoctorCrear').val(doctor);
    });

    $('#codigoCitaEditar').on('change', function () {
    let selected = $(this).find('option:selected');
    let paciente = selected.data('paciente') || '';
    let doctor = selected.data('doctor') || '';

    $('#codigoPacienteEditar').val(paciente);
    $('#codigoDoctorEditar').val(doctor);
});


</script>

@vite('resources/js/app.js')
@stop
