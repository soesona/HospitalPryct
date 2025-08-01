{{-- VISTA PARA QUE LOS PACIENTES AGENDEN SUS CITAS --}}

@extends('adminlte::page')

@section('title', 'Agendar Citas')

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
</style>
@stop

@section('content')
<div class="container">
    <h2></h2>

    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalAgendarCita">
        Agendar Nueva Cita
    </button>

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

    @if ($citas->isNotEmpty())
    @php $cita = $citas->first(); @endphp

    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Próxima Cita</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Doctor:</strong> {{ ucwords(strtolower($cita->doctor->user->nombreCompleto ?? 'N/A')) }}
                </div>
                <div class="col-md-6">
                    <strong>Estado:</strong>
                    <span class="badge badge-{{ $cita->estado == 'pendiente' ? 'warning' : ($cita->estado == 'confirmada' ? 'success' : 'secondary') }}">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Hora:</strong> {{ \Carbon\Carbon::parse($cita->horaInicio)->format('H:i') }}
                </div>
                <div class="col-md-6">
                    <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cita->fechaCita)->format('d/m/Y') }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <strong>Especialidad:</strong> {{ ucfirst(strtolower($cita->doctor->especialidad->nombre ?? 'No definida')) }}
                </div>
                <div class="col-md-6">
                    <strong>Duración:</strong> 30 minutos
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card shadow mb-4 border-left-warning">
        <div class="card-body text-center">
            <h5 class="text-warning">No tienes citas pendientes</h5>
            <p class="mb-0">Puedes agendar una nueva cita cuando lo desees.</p>
        </div>
    </div>
@endif


</div>

{{-- Modal para agendar cita --}}
<div class="modal fade" id="modalAgendarCita" tabindex="-1" role="dialog" aria-labelledby="modalAgendarCitaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('citas.store') }}" method="POST" id="formAgendarCita">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAgendarCitaLabel">Agendar Cita</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

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
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary" id="btnAgendar">Agendar Cita</button>
        </div>
      </div>
    </form>
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
<script>
$(document).ready(function() {
    console.log('Script iniciado'); // Debug
    
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

    // Manejar errores de validación (solo reabrir modal si NO hay notificaciones)
    @if ($errors->any())
    @php
        $keys = $errors->keys();
        $msg = $errors->first();
    @endphp

    @if (!in_array('error', $keys))
        // Si no es un error general, mostrar notificación y reabrir el modal
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
        // Si es un error general, solo mostrar el mensaje sin reabrir el modal
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

// Cuando selecciona especialidad
$('#especialidad').on('change', function () {
    let especialidadId = $(this).val();
    clearDependentFields('especialidad');

    if (especialidadId) {
        $('#codigoDoctor').html('<option value="">Cargando doctores...</option>');

        $.ajax({
            url: `/doctores-por-especialidad/${especialidadId}`,
            method: 'GET',
            success: function(data) {
                console.log('Respuesta doctores:', data);
                $('#codigoDoctor').html('<option value="">-- Selecciona un doctor --</option>');

                if (data.length === 0) {
                    $('#codigoDoctor').append('<option disabled>No hay doctores disponibles</option>');
                    showInfo($('#doctorInfo'), 'No hay doctores disponibles para esta especialidad', 'warning');
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
        // Reactivar botón por si quedó deshabilitado
        $('#btnAgendar').prop('disabled', false).text('Agendar Cita');
    });

    // Validación adicional antes de enviar
    $('#formAgendarCita').on('submit', function(e) {
        let horaSeleccionada = $('#horaInicio').val();
        if (!horaSeleccionada) {
            e.preventDefault();
            mostrarNotificacion('Error', 'Por favor selecciona una hora para la cita.', 'error');
            return false;
        }
        
        // Deshabilitar botón para evitar doble envío
        $('#btnAgendar').prop('disabled', true).text('Agendando...');
        
        // Cerrar modal de agendar para que no interfiera con el modal de notificación
        $('#modalAgendarCita').modal('hide');
    });
});
</script>
@endsection