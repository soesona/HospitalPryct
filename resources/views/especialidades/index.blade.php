@extends('adminlte::page')

@section('title', 'Especialidades')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content_header')
    <h1 class="mb-3">Listado de Especialidades</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearEspecialidad">
                <i class="fas fa-plus"></i> Registrar Nueva Especialidad
            </button>
        </div>

        <div class="card-body">
            <table id="tablaEspecialidades" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
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

    <!-- Modal Crear Especialidad -->
    <div class="modal fade" id="modalCrearEspecialidad" tabindex="-1" role="dialog" aria-labelledby="modalCrearEspecialidadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formCrearEspecialidad" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalCrearEspecialidadLabel">Registrar Nueva Especialidad</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreCrear">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombreCrear" class="form-control" required maxlength="100"
                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$" title="Solo se permiten letras y espacios">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Registrar</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Especialidad -->
    <div class="modal fade" id="modalEditarEspecialidad" tabindex="-1" role="dialog" aria-labelledby="modalEditarEspecialidadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="POST" id="formEditarEspecialidad" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalEditarEspecialidadLabel">Editar Especialidad</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="codigoEspecialidad" id="codigoEspecialidadEditar">
                    <div class="form-group">
                        <label for="nombreEditar">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombreEditar" class="form-control" required maxlength="100"
                        pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$" title="Solo se permiten letras y espacios">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Guardar Cambios</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Notificación de Éxito -->
    <div class="modal fade" id="modalExito" tabindex="-1" role="dialog" aria-labelledby="modalExitoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-success d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-check text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <h5 class="modal-title font-weight-bold text-dark mb-2" id="modalExitoTitulo">Éxito</h5>
                    <p class="text-muted mb-0" id="modalExitoMensaje">Operación completada.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Notificación de Error -->
    <div class="modal fade" id="modalError" tabindex="-1" role="dialog" aria-labelledby="modalErrorLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-danger d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-times text-white" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <h5 class="modal-title font-weight-bold text-dark mb-2" id="modalErrorTitulo">Error</h5>
                    <p class="text-muted mb-0" id="modalErrorMensaje">Ha ocurrido un error.</p>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){

    // Permitir solo letras y espacios en ambos inputs
$('#nombreCrear, #nombreEditar').on('input', function() {
    this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ ]/g, '');
});

   
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
    function mostrarExito(titulo, mensaje) {
        $('#modalExitoTitulo').text(titulo);
        $('#modalExitoMensaje').text(mensaje);
        $('#modalExito').modal('show');
        
        setTimeout(function(){
            $('#modalExito').modal('hide');
        }, 2500);
    }

    function mostrarError(titulo, mensaje) {
        $('#modalErrorTitulo').text(titulo);
        $('#modalErrorMensaje').text(mensaje);
        $('#modalError').modal('show');
        
        setTimeout(function(){
            $('#modalError').modal('hide');
        }, 3500);
    }

    function procesarErroresValidacion(xhr) {
        let mensaje = 'Error inesperado';
        
        if (xhr.responseJSON) {
            if (xhr.responseJSON.errors) {
                const errores = xhr.responseJSON.errors;
                const primerError = Object.values(errores)[0];
                mensaje = Array.isArray(primerError) ? primerError[0] : primerError;
            }
            else if (xhr.responseJSON.message) {
                mensaje = xhr.responseJSON.message;
            }
        }
        
        return mensaje;
    }

    
    $('#formCrearEspecialidad').submit(function(e){
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Registrando...');

        $.ajax({
            url: '{{ route("especialidades.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response){
                console.log('Especialidad creada:', response);
                $('#modalCrearEspecialidad').modal('hide');
                mostrarExito('Especialidad registrada', 'La especialidad fue registrada correctamente.');
                
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr){
                console.error('Error creando especialidad:', xhr);
                console.error('Response:', xhr.responseText);
                const mensajeError = procesarErroresValidacion(xhr);
                mostrarError('Error de validación', mensajeError);
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    
    $('#formEditarEspecialidad').submit(function(e){
        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Guardando...');

        const especialidadId = $('#codigoEspecialidadEditar').val();

        $.ajax({
            url: `/especialidades/${especialidadId}`,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response){
                console.log('Especialidad actualizada:', response);
                $('#modalEditarEspecialidad').modal('hide');
                mostrarExito('Especialidad actualizada', 'Los cambios fueron guardados correctamente.');
                
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr){
                console.error('Error actualizando especialidad:', xhr);
                console.error('Response:', xhr.responseText);
                const mensajeError = procesarErroresValidacion(xhr);
                mostrarError('Error de validación', mensajeError);
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    
    $(document).on('click', '.btnEditarEspecialidad', function() {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');

        $('#codigoEspecialidadEditar').val(id);
        $('#nombreEditar').val(nombre);
    });

    
    $('#modalCrearEspecialidad').on('hidden.bs.modal', function () {
        $('#formCrearEspecialidad')[0].reset();
        $('#formCrearEspecialidad .form-control').removeClass('is-invalid');
    });

    $('#modalEditarEspecialidad').on('hidden.bs.modal', function () {
        $('#formEditarEspecialidad .form-control').removeClass('is-invalid');
    });

    
});
</script>

@stop