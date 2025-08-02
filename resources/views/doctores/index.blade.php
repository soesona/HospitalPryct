@extends('adminlte::page')

@section('title', 'Doctores')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />

@stop

@section('content_header')
    <h1>Doctores registrados</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <a href="{{ route('doctores.pdf') }}" class="btn btn-secondary">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>


   <div class="card-body table-responsive">
        <table id="pagination-table" class="table table-bordered table-striped">
        <thead>
            <tr>
            <th>Código Doctor</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Estado</th>
            <th>Especialidad</th>
            <th>Horarios</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($doctores as $doctor)
        <tr>
            <td>
                @if($doctor->doctor)
                    {{ $doctor->doctor->codigoDoctor }}
                @else
                    <em>No hay registro</em>
                @endif
            </td>
            <td>{{ ucwords(strtolower($doctor->nombreCompleto)) }}</td>
            <td>{{ $doctor->email }}</td>
            <td>{{ $doctor->telefono }}</td>
            <td>
                @if($doctor->doctor)
                    <span class="badge bg-success">Con registro</span>
                @else
                    <span class="badge bg-danger">Sin registro</span>
                @endif
            </td>
            <td>
                @if($doctor->doctor && $doctor->doctor->especialidad)
                    {{ ucfirst(strtolower($doctor->doctor->especialidad->nombre)) }}
                @else
                    <em>No aplica</em>
                @endif
            </td>
            <td>
            @if($doctor->doctor && $doctor->doctor->horarios->count())
                <ul class="list-unstyled">
                    @foreach($doctor->doctor->horarios as $horario)
                        <li>
                            {{ ucfirst($horario->diaSemana) }}: 
                            {{ \Carbon\Carbon::parse($horario->horaInicio)->format('g:i A') }} - 
                            {{ \Carbon\Carbon::parse($horario->horaFin)->format('g:i A') }}
                        </li>
                    @endforeach
                </ul>
            @else
                <em>No aplica</em>
            @endif
        </td>
            <td>
                @if($doctor->doctor)
                    <button class="btn btn-sm btn-outline-primary editar-registro" data-id="{{ $doctor->codigoUsuario }}">
                        Editar
                    </button>
                @else
                    <button class="btn btn-sm btn-outline-success crear-registro" data-id="{{ $doctor->codigoUsuario }}">
                        Crear Registro
                    </button>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<!-- Modal Crear Registro -->
<div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-labelledby="modalCrearLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formCrear" method="POST" action="">
        @csrf
        <input type="hidden" name="codigoUsuario" id="crearCodigoUsuario">

        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearLabel">Crear Registro de Doctor</h5>
        </div>

        <div class="modal-body">
          <div class="form-group mb-3">
            <label>Especialidad</label>
            <select class="form-control" name="especialidad" id="crearEspecialidad" required>
              <option value="">-- Selecciona una especialidad --</option>
              @foreach($especialidades as $esp)
                <option value="{{ $esp->codigoEspecialidad }}">{{ $esp->nombre }}</option>
              @endforeach
            </select>
          </div>

          <div id="crearHorariosContainer"></div>
          <button type="button" class="btn btn-secondary mt-2" id="crearAgregarHorario">+ Agregar horario</button>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Registro</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Editar Registro -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="formEditar" action="{{ route('doctores.guardar-registro') }}">

        @csrf
        <input type="hidden" name="codigoUsuario" id="editarCodigoUsuario">

        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Registro de Doctor</h5>
        </div>

        <div class="modal-body">
          <div class="form-group mb-3">
            <label>Especialidad</label>
            <select class="form-control" name="especialidad" id="editarEspecialidad" required>
              <option value="">-- Selecciona una especialidad --</option>
              @foreach($especialidades as $esp)
                <option value="{{ $esp->codigoEspecialidad }}">{{ $esp->nombre }}</option>
              @endforeach
            </select>
          </div>

          <div id="editarHorariosContainer"></div>
          <button type="button" class="btn btn-secondary mt-2" id="editarAgregarHorario">+ Agregar horario</button>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Editar Notificacion -->
<div class="modal fade" id="modalNotificacion" tabindex="-1" role="dialog" aria-labelledby="modalNotificacionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-body text-center p-4">
        <div class="mb-3">
          <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
            <i class="fas fa-check text-white" style="font-size: 24px;"></i>
          </div>
        </div>
        <h5 class="modal-title font-weight-bold text-dark mb-2" id="modalNotificacionTitulo">Éxito</h5>
        <p class="text-muted mb-0" id="modalNotificacionMensaje">Operación completada.</p>
      </div>
    </div>
  </div>
</div>

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

$(document).ready(function(){
  console.log('Script cargado correctamente');

  function mostrarNotificacion(titulo, mensaje) {
    $('#modalNotificacionTitulo').text(titulo);
    $('#modalNotificacionMensaje').text(mensaje);
    $('#modalNotificacion').modal('show');
    
    setTimeout(function(){
      $('#modalNotificacion').modal('hide');
    }, 2500);
  }

  function crearHorarioHTML(index, dia = '', inicio = '', fin = '') {
    return `
      <div class="row mb-2 horario-item">
        <div class="col">
          <select name="horarios[${index}][dia]" class="form-control" required>
            <option value="">-- Seleccionar día --</option>
            <option value="Lunes" ${dia === 'Lunes' ? 'selected' : ''}>Lunes</option>
            <option value="Martes" ${dia === 'Martes' ? 'selected' : ''}>Martes</option>
            <option value="Miércoles" ${dia === 'Miércoles' ? 'selected' : ''}>Miércoles</option>
            <option value="Jueves" ${dia === 'Jueves' ? 'selected' : ''}>Jueves</option>
            <option value="Viernes" ${dia === 'Viernes' ? 'selected' : ''}>Viernes</option>
            <option value="Sábado" ${dia === 'Sábado' ? 'selected' : ''}>Sábado</option>
            <option value="Domingo" ${dia === 'Domingo' ? 'selected' : ''}>Domingo</option>
          </select>
        </div>
        <div class="col">
          <input type="time" name="horarios[${index}][hora_inicio]" class="form-control" value="${inicio}" required>
        </div>
        <div class="col">
          <input type="time" name="horarios[${index}][hora_fin]" class="form-control" value="${fin}" required>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-danger btn-sm btn-eliminar-horario">Quitar</button>
        </div>
      </div>
    `;
  }

  function limpiarModalCrear() {
    $('#crearCodigoUsuario').val('');
    $('#crearEspecialidad').val('');
    $('#crearHorariosContainer').empty();
  }

  function limpiarModalEditar() {
    $('#editarCodigoUsuario').val('');
    $('#editarEspecialidad').val('');
    $('#editarHorariosContainer').empty();
  }

  $('#crearAgregarHorario').click(function(){
    const count = $('#crearHorariosContainer .horario-item').length;
    $('#crearHorariosContainer').append(crearHorarioHTML(count));
  });

  $('#editarAgregarHorario').click(function(){
    const count = $('#editarHorariosContainer .horario-item').length;
    $('#editarHorariosContainer').append(crearHorarioHTML(count));
  });

  $(document).on('click', '.crear-registro', function(e){
    e.preventDefault();
    limpiarModalCrear();
    const codigoUsuario = $(this).data('id');
    $('#crearCodigoUsuario').val(codigoUsuario);
    $('#crearAgregarHorario').click();
    $('#modalCrear').modal('show');
  });

  $(document).on('click', '.editar-registro', function(e){
    e.preventDefault();
    limpiarModalEditar();
    const codigoUsuario = $(this).data('id');
    $('#editarCodigoUsuario').val(codigoUsuario);

    $.ajax({
      url: '/doctores/obtener-datos/' + codigoUsuario,
      method: 'GET',
      success: function(data){
        $('#editarEspecialidad').val(data.doctor.codigoEspecialidad);
        
        $('#editarHorariosContainer').empty();
        
        if (data.horarios && data.horarios.length > 0) {
          data.horarios.forEach(function(h, i){
            const diasMap = {
              'lunes': 'Lunes',
              'martes': 'Martes', 
              'miércoles': 'Miércoles',
              'miercoles': 'Miércoles',
              'jueves': 'Jueves',
              'viernes': 'Viernes',
              'sábado': 'Sábado',
              'sabado': 'Sábado',
              'domingo': 'Domingo'
            };
            let diaCorregido = diasMap[h.diaSemana.toLowerCase()] || h.diaSemana;

            $('#editarHorariosContainer').append(crearHorarioHTML(i, diaCorregido, h.horaInicio, h.horaFin));
          });
        } else {
          $('#editarHorariosContainer').append(crearHorarioHTML(0));
        }

        $('#modalEditar').modal('show');
      },
      error: function() {
        alert('Error al cargar los datos del doctor');
      }
    });
  });

  $('#formCrear').submit(function(e){
    e.preventDefault();

    if ($('#crearHorariosContainer .horario-item').length === 0) {
      alert('Debe agregar al menos un horario.');
      return false;
    }

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).text('Guardando...');

    $.ajax({
      url: '{{ route("doctores.crear-registro") }}', 
      method: 'POST',
      data: $(this).serialize(),
      success: function(response){
        $('#modalCrear').modal('hide');
        mostrarNotificacion('Registro creado', 'El registro del doctor fue creado correctamente.');
        setTimeout(function() {
          window.location.reload();
        }, 1500);
      },
      error: function(xhr){
        alert('Error: ' + (xhr.responseJSON?.message || 'Error inesperado'));
      },
      complete: function() {
        submitBtn.prop('disabled', false).text('Guardar Registro');
      }
    });
  });

  $('#formEditar').submit(function(e){
    e.preventDefault();

    if ($('#editarHorariosContainer .horario-item').length === 0) {
      alert('Debe agregar al menos un horario.');
      return false;
    }

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).text('Guardando...');

    $.ajax({
      url: '{{ route("doctores.guardar-registro") }}', 
      method: 'POST',
      data: $(this).serialize(),
      success: function(response){
        $('#modalEditar').modal('hide');
        mostrarNotificacion('Cambios guardados', 'Los cambios fueron guardados correctamente.');
        setTimeout(function() {
          window.location.reload();
        }, 1500);
      },
      error: function(xhr){
        alert('Error: ' + (xhr.responseJSON?.message || 'Error inesperado'));
      },
      complete: function() {
        submitBtn.prop('disabled', false).text('Guardar Cambios');
      }
    });
  });

  $(document).on('click', '.btn-eliminar-horario', function(){
    $(this).closest('.horario-item').remove();
  });

});

</script>

@vite('resources/js/app.js') 
@stop
