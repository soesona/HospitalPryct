{{-- VISTA PARA QUE LOS ADMINISTRADORES GESTIONEN LAS CITAS --}}

@extends('adminlte::page')

@section('title', 'Gestión de Citas')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
<style>
.loading-text {
    color: #6c757d;
    font-style: italic;
}
.no-schedule-text {
    color: #dc3545;
    font-style: italic;
}
.alert-custom {
    margin-top: 10px;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 14px;
}
.alert-info-custom {
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}
.alert-warning-custom {
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    color: #856404;
}
.alert-danger-custom {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}
.patient-search-result {
    cursor: pointer;
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}
.patient-search-result:hover {
    background-color: #f8f9fa;
}
.patient-search-result:last-child {
    border-bottom: none;
}
.search-results-container {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}
.form-group-search {
    position: relative;
}
.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}
</style>
@stop

@section('content')
<div class="container-fluid">
  <div class="mb-3">
     <h1><span class="h3 font-weight-bold">Gestión de citas</span></h1>

    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgendarCita">
        <i class="fas fa-plus"></i> Agendar Nueva Cita
    </button>

    <a href="{{ route('citas.pdf', ['estado' => 'pendiente']) }}" class="btn btn-warning">
        <i class="fas fa-file-pdf"></i> Citas Pendientes
    </a>
    <a href="{{ route('citas.pdf', ['estado' => 'confirmada']) }}" class="btn btn-success">
        <i class="fas fa-file-pdf"></i> Citas Confirmadas
    </a>
    <a href="{{ route('citas.pdf', ['estado' => 'cancelada']) }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Citas Canceladas
    </a>
    <a href="{{ route('citas.pdf') }}" class="btn btn-secondary">
        <i class="fas fa-file-pdf"></i> Todas las Citas
    </a>
</div>

    @if ($errors->any())
        <div class="d-none" id="validation-errors">
            @foreach ($errors->all() as $error)
                <span data-error="{{ $error }}"></span>
            @endforeach
        </div>
    @endif

    @if (session('success'))
        <div class="d-none" id="success-message" data-message="{{ session('success') }}"></div>
    @endif

    @if (session('error'))
        <div class="d-none" id="error-message" data-message="{{ session('error') }}"></div>
    @endif

    {{-- Tabla de citas --}}
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Todas las Citas</h5>
        </div>
        <div class="card-body">
            @if ($citas->isNotEmpty())
                <div class="table-responsive">
                    <input type="text" id="buscarIdentidad" class="form-control mb-3" placeholder="Buscar paciente por identidad...">
                    <table class="table table-striped table-hover" id="tablaCitas">
                        <thead class="thead-dark">
                            <tr>
                                <th>Identidad</th>
                                <th>Paciente</th>
                                <th>Doctor</th>
                                <th>Especialidad</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($citas as $cita)
                                    <tr>
                                    <td>{{ $cita->paciente->usuario->identidad ?? 'N/A' }}</td>
                                    <td>{{ ucwords(strtolower($cita->paciente->usuario->nombreCompleto ?? 'N/A')) }}</td>
                                    <td>{{ ucwords(strtolower($cita->doctor->user->nombreCompleto ?? 'N/A')) }}</td>
                                    <td>{{ ucfirst(strtolower($cita->doctor->especialidad->nombre ?? 'No definida')) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cita->fechaCita)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($cita->horaInicio)->format('H:i') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $cita->estado == 'pendiente' ? 'warning' : ($cita->estado == 'confirmada' ? 'success' : 'danger') }}">
                                            {{ ucfirst($cita->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($cita->estado == 'pendiente')
                                            <button class="btn btn-sm btn-info"
                                                onclick="cambiarEstadoCita(
                                                    {{ $cita->codigoCita }},
                                                    '{{ $cita->estado }}',
                                                    '{{ ucwords(strtolower($cita->paciente->usuario->nombreCompleto ?? 'N/A')) }}',
                                                    '{{ \Carbon\Carbon::parse($cita->fechaCita)->format('d/m/Y') }}',
                                                    '{{ \Carbon\Carbon::parse($cita->horaInicio)->format('H:i') }}'
                                                )"
                                                title="Cambiar Estado">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @else
                                            <span class="text-muted" title="Solo se pueden editar citas pendientes">
                                                <i class="fas fa-lock"></i> No editable
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay citas registradas</h5>
                    <p class="text-muted">No se han agendado citas aún.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal para agendar cita --}}
<div class="modal fade" id="modalAgendarCita" tabindex="-1" role="dialog" aria-labelledby="modalAgendarCitaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('admin.citas.store') }}" method="POST" id="formAgendarCita">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgendarCitaLabel">
                        <i class="fas fa-calendar-plus"></i> Agendar Cita para Paciente
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Búsqueda de Paciente --}}
                    <div class="form-group form-group-search">
                        <label for="buscarPaciente">Paciente:</label>
                        <input type="text" class="form-control" id="buscarPaciente" 
                               placeholder="Escriba la identidad del paciente..." autocomplete="off">
                        <input type="hidden" name="codigoPaciente" id="codigoPaciente" required>
                        <div class="search-results-container" id="resultadosBusqueda"></div>
                        <div id="pacienteSeleccionado" class="alert-custom alert-info-custom" style="display: none;"></div>
                    </div>

                    {{-- Especialidad --}}
                    <div class="form-group">
                        <label for="especialidad">Especialidad:</label>
                        <select class="form-control" id="especialidad" name="codigoEspecialidad" required>
                            <option value="">-- Selecciona una especialidad --</option>
                            @foreach ($especialidades as $esp)
                                <option value="{{ $esp->codigoEspecialidad }}">{{ ucwords(strtolower($esp->nombre)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Doctor --}}
                    <div class="form-group">
                        <label for="codigoDoctor">Doctor:</label>
                        <select name="codigoDoctor" class="form-control" id="codigoDoctor" required>
                            <option value="">-- Primero selecciona una especialidad --</option>
                        </select>
                        <div id="doctorInfo" class="alert-custom alert-info-custom" style="display: none;"></div>
                    </div>

                    {{-- Fecha --}}
                    <div class="form-group">
                        <label for="fechaCita">Fecha:</label>
                        <input type="date" name="fechaCita" class="form-control" id="fechaCita"
                               min="{{ \Carbon\Carbon::today()->toDateString() }}" required>
                        <small class="form-text text-muted">Solo puedes agendar citas desde hoy en adelante</small>
                    </div>

                    {{-- Hora --}}
                    <div class="form-group">
                        <label for="horaInicio">Hora:</label>
                        <select name="horaInicio" class="form-control" id="horaInicio" required>
                            <option value="">-- Primero selecciona doctor y fecha --</option>
                        </select>
                        <div id="horaInfo" class="alert-custom" style="display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnAgendar">
                        <i class="fas fa-calendar-check"></i> Agendar Cita
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal para cambiar estado de cita --}}
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formCambiarEstado" method="POST">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCambiarEstadoLabel">
                        <i class="fas fa-edit"></i> Cambiar Estado de Cita
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nuevoEstado">Nuevo Estado:</label>
                        <select name="estado" class="form-control" id="nuevoEstado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal de confirmación para cambio de estado --}}
<div class="modal fade" id="modalConfirmarCambio" tabindex="-1" role="dialog" aria-labelledby="modalConfirmarCambioLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalConfirmarCambioLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirmar Cambio de Estado
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-question-circle text-warning" style="font-size: 48px;"></i>
                </div>
                <p class="mb-2">¿Estás seguro de que deseas cambiar el estado de la cita?</p>
                <p class="mb-0"><strong>Paciente: </strong><span id="confirmNombrePaciente"></span></p>
                <p class="mb-0"><strong>Fecha: </strong><span id="confirmFechaCita"></span></p>
                <p class="mb-0"><strong>Hora: </strong><span id="confirmHoraCita"></span></p>
                <p class="mb-0"><strong>Estado actual:</strong> <span id="estadoActualText" ></span></p>
                <p class="mb-0"><strong>Nuevo estado:</strong> <span id="nuevoEstadoText" ></span></p>
                <div class="mt-3">
                    <small class="text-muted">Al cambiar el estado, esta cita ya no podrá ser editada nuevamente.</small>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-warning" id="btnConfirmarCambio">
                    <i class="fas fa-check"></i> Sí, Cambiar Estado
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de notificación -->
<div class="modal fade" id="modalNotificacion" tabindex="-1" role="dialog" aria-labelledby="modalNotificacionTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNotificacionTitulo"></h5>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3"></div> {{-- Aquí se insertará el ícono dinámico --}}
                <p id="modalNotificacionMensaje" class="mb-0"></p>
            </div>
        </div>
    </div>
</div>



@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>


<script>
$(document).ready(function() {
    console.log('Script iniciado'); // Debug
    
    let pacienteSeleccionadoId = null;
    let searchTimeout = null;
    
    function mostrarNotificacion(titulo, mensaje, tipo = 'success') {
        console.log('Mostrando notificación:', titulo, mensaje, tipo); // Debug
        
        let icono = 'check';
        let color = 'bg-success';

        if (tipo === 'error') {
            icono = 'times';
            color = 'bg-danger';
        }

        $('#modalNotificacionTitulo').text(titulo);
        $('#modalNotificacionMensaje').text(mensaje);

        const iconElement = `
            <div class="rounded-circle ${color} d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="fas fa-${icono} text-white" style="font-size: 24px;"></i>
            </div>
        `;

        $('#modalNotificacion .modal-body > div:first').html(iconElement);
        $('#modalNotificacion').modal('show');

        setTimeout(function () {
            $('#modalNotificacion').modal('hide');
        }, 5000);
    }

    // Manejar mensajes de sesión del servidor
    @if (session('success'))
        console.log('Mensaje de éxito recibido'); // Debug
        mostrarNotificacion('¡Éxito!', '{{ session('success') }}', 'success');
    @endif

    @if (session('error'))
        console.log('Mensaje de error recibido'); // Debug
        mostrarNotificacion('Error', '{{ addslashes(session('error')) }}', 'error');
    @endif

    // Manejar errores de validación
    @if ($errors->any())
        @php
            $keys = $errors->keys();
            $msg = $errors->first();
        @endphp

        @if (!in_array('error', $keys))
            mostrarNotificacion('Error de Validación', '{{ addslashes($msg) }}', 'error');

            setTimeout(function () {
                $('#modalAgendarCita').modal('show');

                // Rellenar campos antiguos
                @if (old('codigoEspecialidad'))
                    $('#especialidad').val('{{ old('codigoEspecialidad') }}').trigger('change');
                @endif

                setTimeout(() => {
                    @if (old('codigoDoctor'))
                        $('#codigoDoctor').val('{{ old('codigoDoctor') }}');
                    @endif
                    @if (old('fechaCita'))
                        $('#fechaCita').val('{{ old('fechaCita') }}');
                    @endif

                    setTimeout(() => {
                        @if (old('horaInicio'))
                            $('#horaInicio').val('{{ old('horaInicio') }}');
                        @endif
                    }, 500);
                }, 1000);
            }, 2600);
        @else
            mostrarNotificacion('Error', '{{ addslashes($msg) }}', 'error');
        @endif
    @endif

    // Función para mostrar mensajes informativos
    function showInfo(element, message, type = 'info') {
        element.removeClass('alert-info-custom alert-warning-custom alert-danger-custom')
               .addClass(`alert-${type}-custom`)
               .html(message)
               .show();
    }

    // Función para limpiar campos dependientes
    function clearDependentFields(fromField) {
        if (fromField === 'especialidad') {
            $('#codigoDoctor').html('<option value="">-- Primero selecciona una especialidad --</option>');
            $('#horaInicio').html('<option value="">-- Primero selecciona doctor y fecha --</option>');
            $('#doctorInfo').hide();
            $('#horaInfo').hide();
        } else if (fromField === 'doctor') {
            $('#horaInicio').html('<option value="">-- Primero selecciona doctor y fecha --</option>');
            $('#horaInfo').hide();
        }
    }

    // Función para poner en mayúscula la primera letra de cada palabra
    function capitalizarTexto(texto) {
        return texto.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
    }

    // Búsqueda de pacientes por identidad
$('#buscarPaciente').on('input', function() {
    const query = $(this).val().trim();
    
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    
    if (query.length < 4) {
        $('#resultadosBusqueda').hide().empty();
        pacienteSeleccionadoId = null;
        $('#codigoPaciente').val('');
        $('#pacienteSeleccionado').hide();
        return;
    }

    searchTimeout = setTimeout(() => {
        $.ajax({
            url: '/admin/buscar-pacientes',
            method: 'GET',
            data: { q: query },
            success: function(data) {
                console.log('Resultados búsqueda:', data);
                const container = $('#resultadosBusqueda');
                container.empty();

                if (data.length === 0) {
                    container.html('<div class="patient-search-result text-muted"><i class="fas fa-search"></i> No se encontraron pacientes con esa identidad</div>');
                } else {
                    data.forEach(paciente => {
                        const nombre = capitalizarTexto(paciente.usuario.nombreCompleto);
                        const identidad = paciente.usuario.identidad;
                        
                        const elemento = `
                            <div class="patient-search-result" data-id="${paciente.codigoPaciente}" data-nombre="${nombre}" data-identidad="${identidad}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-primary">${nombre}</strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card mr-1"></i>Identidad: ${identidad}
                                        </small><br>
                                        <small class="text-muted">
                                            <i class="fas fa-hashtag mr-1"></i>Código: ${paciente.codigoPaciente}
                                        </small>
                                    </div>
                                    <div class="text-right">
                                        <i class="fas fa-user-plus text-success"></i>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(elemento);
                    });
                }
                
                container.show();
            },
            error: function(xhr, status, error) {
                console.error('Error en búsqueda:', error);
                $('#resultadosBusqueda').html('<div class="patient-search-result text-danger"><i class="fas fa-exclamation-triangle"></i> Error al buscar pacientes</div>').show();
            }
        });
    }, 300);
});

    // Seleccionar paciente de los resultados
$(document).on('click', '.patient-search-result[data-id]', function() {
    const id = $(this).data('id');
    const nombre = $(this).data('nombre');
    const identidad = $(this).data('identidad');
    
    pacienteSeleccionadoId = id;
    $('#codigoPaciente').val(id);
    
    // Mostrar la identidad en el campo de búsqueda en lugar del nombre
    // ya que el usuario buscó por identidad
    $('#buscarPaciente').val(identidad);
    $('#resultadosBusqueda').hide().empty();
    
    // Mensaje más informativo mostrando nombre e identidad
    showInfo($('#pacienteSeleccionado'), 
        `Paciente seleccionado: <strong>${nombre}</strong> (ID: ${identidad})`, 
        'info'
    );
    
    // Limpiar campos dependientes del paciente
    clearDependentFields('especialidad');
});

    // Ocultar resultados al hacer clic fuera
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.form-group-search').length) {
            $('#resultadosBusqueda').hide();
        }
    });

    // Cuando selecciona especialidad
    $('#especialidad').on('change', function () {
        let especialidadId = $(this).val();
        clearDependentFields('especialidad');

        if (especialidadId && pacienteSeleccionadoId) {
            $('#codigoDoctor').html('<option value="">Cargando doctores...</option>');

            $.ajax({
                url: `/admin/doctores-por-especialidad/${especialidadId}/${pacienteSeleccionadoId}`,
                method: 'GET',
                success: function(data) {
                    console.log('Respuesta doctores:', data);
                    $('#codigoDoctor').html('<option value="">-- Selecciona un doctor --</option>');

                    if (data.length === 0) {
                        $('#codigoDoctor').append('<option disabled>No hay doctores disponibles</option>');
                        showInfo($('#doctorInfo'), 'No hay doctores disponibles para esta especialidad o el paciente es doctor en esta especialidad', 'warning');
                    } else {
                        data.forEach(doc => {
                            let nombreOriginal = doc.user ? doc.user.nombreCompleto : 'Sin nombre';
                            let nombreFormateado = capitalizarTexto(nombreOriginal);

                            $('#codigoDoctor').append(`<option value="${doc.codigoDoctor}">${nombreFormateado}</option>`);
                        });
                        showInfo($('#doctorInfo'), `${data.length} doctor(es) disponible(s)`, 'info');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar doctores:', error);
                    $('#codigoDoctor').html('<option value="">Error al cargar doctores</option>');
                    showInfo($('#doctorInfo'), 'Error al cargar doctores. Intenta de nuevo.', 'danger');
                }
            });
        } else if (!pacienteSeleccionadoId) {
            showInfo($('#doctorInfo'), 'Primero selecciona un paciente', 'warning');
        }
    });

    // Función para cargar horas disponibles
    function loadAvailableHours() {
        let doctorId = $('#codigoDoctor').val();
        let fecha = $('#fechaCita').val();
        
        if (doctorId && fecha) {
            $('#horaInicio').html('<option value="">Cargando horas...</option>');
            $('#horaInfo').hide();
            
            $.ajax({
                url: `/horas-disponibles/${doctorId}/${fecha}`,
                method: 'GET',
                success: function(data) {
                    console.log('Respuesta horas:', data);
                    $('#horaInicio').html('<option value="">-- Selecciona una hora --</option>');
                    
                    if (data.no_horario) {
                        $('#horaInicio').append('<option disabled>El doctor no atiende este día</option>');
                        showInfo($('#horaInfo'), 'El doctor no tiene horario de atención para este día. Selecciona otra fecha.', 'warning');
                        return;
                    }
                    
                    if (data.length === 0) {
                        $('#horaInicio').append('<option disabled>No hay horas disponibles</option>');
                        showInfo($('#horaInfo'), 'No hay horas disponibles para esta fecha. Todas las horas están ocupadas o ya pasaron.', 'warning');
                    } else {
                        data.forEach(hora => {
                            let horaFormateada = hora.substring(0, 5);
                            $('#horaInicio').append(`<option value="${horaFormateada}">${horaFormateada}</option>`);
                        });
                        
                        let fechaSeleccionada = new Date(fecha);
                        let hoy = new Date();
                        let esHoy = fechaSeleccionada.toDateString() === hoy.toDateString();
                        
                        if (esHoy) {
                            showInfo($('#horaInfo'), `${data.length} hora(s) disponible(s) para hoy (las horas pasadas no se muestran)`, 'info');
                        } else {
                            showInfo($('#horaInfo'), `${data.length} cita(s) disponible(s) para esta fecha`, 'info');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar horas:', error);
                    $('#horaInicio').html('<option value="">Error al cargar horas</option>');
                    showInfo($('#horaInfo'), 'Error al cargar horarios. Verifica tu conexión e intenta de nuevo.', 'danger');
                }
            });
        } else {
            clearDependentFields('doctor');
        }
    }

    // Eventos para cargar horas
    $('#codigoDoctor').on('change', loadAvailableHours);
    $('#fechaCita').on('change', loadAvailableHours);

    // Limpiar formulario al cerrar modal
    $('#modalAgendarCita').on('hidden.bs.modal', function () {
        $('#formAgendarCita')[0].reset();
        clearDependentFields('especialidad');
        $('#resultadosBusqueda').hide().empty();
        $('#pacienteSeleccionado').hide();
        pacienteSeleccionadoId = null;
        $('#btnAgendar').prop('disabled', false).html('<i class="fas fa-calendar-check"></i> Agendar Cita');
    });

    // Validación adicional antes de enviar
    $('#formAgendarCita').on('submit', function(e) {
        if (!pacienteSeleccionadoId) {
            e.preventDefault();
            mostrarNotificacion('Error', 'Por favor selecciona un paciente.', 'error');
            return false;
        }
        
        let horaSeleccionada = $('#horaInicio').val();
        if (!horaSeleccionada) {
            e.preventDefault();
            mostrarNotificacion('Error', 'Por favor selecciona una hora para la cita.', 'error');
            return false;
        }
        
        // Deshabilitar botón para evitar doble envío
        $('#btnAgendar').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Agendando...');
        
        // Cerrar modal de agendar para que no interfiera con el modal de notificación
        $('#modalAgendarCita').modal('hide');
    });
});

// Función para cambiar estado de cita
let citaParaCambiar = {
    codigo: null,
    estadoActual: null,
    nuevoEstado: null,
    nombrePaciente: null,
    fechaCita: null,
    horaCita: null
};

function cambiarEstadoCita(codigoCita, estadoActual, nombrePaciente, fechaCita, horaCita) {
    citaParaCambiar.codigo = codigoCita;
    citaParaCambiar.estadoActual = estadoActual;
    citaParaCambiar.nuevoEstado = estadoActual;
    
    // Guardar los datos adicionales
    citaParaCambiar.nombrePaciente = nombrePaciente;
    citaParaCambiar.fechaCita = fechaCita;
    citaParaCambiar.horaCita = horaCita;

    $('#confirmNombrePaciente').text(nombrePaciente);
    $('#confirmFechaCita').text(fechaCita);
    $('#confirmHoraCita').text(horaCita);

    $('#nuevoEstado').val(estadoActual);
    $('#formCambiarEstado').attr('action', `/admin/citas/${codigoCita}/estado`);
    $('#modalCambiarEstado').modal('show');
}

$(document).ready(function() {
    // Interceptar submit del formulario del primer modal
    $('#formCambiarEstado').on('submit', function(e) {
        e.preventDefault();

        let nuevoEstadoSeleccionado = $('#nuevoEstado').val();
        citaParaCambiar.nuevoEstado = nuevoEstadoSeleccionado;

        // Mostrar confirmación solo si cambia el estado
        if (citaParaCambiar.estadoActual === nuevoEstadoSeleccionado) {
            alert('No has cambiado el estado.');
            return;
        }

        // Mostrar texto con los estados en el modal de confirmación
        $('#estadoActualText').text(citaParaCambiar.estadoActual);
        $('#nuevoEstadoText').text(nuevoEstadoSeleccionado);
        $('#confirmNombrePaciente').text(citaParaCambiar.nombrePaciente);
        $('#confirmFechaCita').text(citaParaCambiar.fechaCita);
        $('#confirmHoraCita').text(citaParaCambiar.horaCita);
        
        // Ocultar primer modal y mostrar el de confirmación
        $('#modalCambiarEstado').modal('hide');
        $('#modalConfirmarCambio').modal('show');
    });

    // Botón de confirmar cambio en segundo modal
    $('#btnConfirmarCambio').on('click', function() {
        // Cerrar modal de confirmación
        $('#modalConfirmarCambio').modal('hide');

        // Aquí haces el envío real con AJAX o submit, por ejemplo AJAX:
        $.ajax({
            url: `/admin/citas/${citaParaCambiar.codigo}/estado`,
            method: 'PATCH',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                estado: citaParaCambiar.nuevoEstado
            },
            success: function() {
                // Mostrar modal éxito
                $('#modalNotificacionTitulo').text('¡Éxito!');
                $('#modalNotificacionMensaje').text('El estado de la cita ha sido actualizado.');
                $('#modalNotificacion').modal('show');

                // Recargar o actualizar tabla después de unos segundos
                setTimeout(() => {
                    location.reload();
                }, 2000);
            },
            error: function() {
                alert('Error al actualizar el estado.');
            }
        });
    });

    // Botón cancelar en modalConfirmarCambio simplemente cierra el modal
    $('#modalConfirmarCambio .btn-secondary').on('click', function() {
        $('#modalConfirmarCambio').modal('hide');
        $('#modalCambiarEstado').modal('hide');
    });
});


// Función para cancelar cita
function cancelarCita(codigoCita) {
    if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        $.ajax({
            url: `/admin/citas/${codigoCita}/estado`,
            method: 'PATCH',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                estado: 'cancelada'
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Error al cancelar la cita');
            }
        });
    }
}

</script>
@vite('resources/js/app.js') 
@endsection