@extends('adminlte::page')

@section('title', 'Medicamentos')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content_header')
    <h1 class="mb-3">Listado de Medicamentos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrear">
                <i class="fas fa-plus"></i> Registrar Nuevo Medicamento
            </button>
        </div>

        <div class="card-body">
            <table id="tablaMedicamentos" class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Editar</th>
                        <th>Descontinuar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaMedicamentos as $medicamento)
                        <tr>
                            <td>{{ $medicamento->codigoMedicamento }}</td>
                            <td>{{ ucfirst(strtolower($medicamento->nombre)) }}</td>
                            <td>{{ ucfirst(strtolower($medicamento->descripcion)) }}</td>
                            <td>{{ $medicamento->stock }}</td>
                            <td>{{ $medicamento->fechaVencimiento }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm ejecutar"
                                    data-toggle="modal" data-target="#mEditarMedicamento"
                                    data-codigomed="{{ $medicamento->codigoMedicamento }}"
                                    data-nombre="{{ ucfirst(strtolower($medicamento->nombre)) }}"
                                    data-descripcion="{{ucfirst(strtolower($medicamento->descripcion)) }}"
                                    data-stock="{{ $medicamento->stock }}"
                                    data-fechav="{{ $medicamento->fechaVencimiento }}">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                            </td>
                            <td>
                                <form id="estado-form-{{ $medicamento->codigoMedicamento }}" action="{{ route('medicamento.cambiarEstado', $medicamento->codigoMedicamento) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="button" 
                                    class="btn btn-sm {{ $medicamento->activo ? 'btn-danger' : 'btn-success' }}" 
                                    onclick="confirmarCambioEstado({{ $medicamento->codigoMedicamento }}, '{{ $medicamento->activo ? 'desactivar' : 'activar' }}')">
                                    <i class="fas {{ $medicamento->activo ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                    {{ $medicamento->activo ? 'Descontinuar' : 'Reactivar' }}
                                </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Crear Medicamento -->
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <form id="formCrearMedicamento" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Medicamento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                       <input type="text" name="nombre" id="nombre" class="form-control" required maxlength="100">
                        <small id="error-nombre" class="text-danger d-none">El nombre no puede iniciar con un número.</small>
                    </div>
                    <div class="form-group">
                        <label>Descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control" required maxlength="500" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Stock <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Vencimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fechaVencimiento" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Medicamento -->
    <div class="modal fade" id="mEditarMedicamento" tabindex="-1">
        <div class="modal-dialog">
            <form id="formEditarMedicamento" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Editar Medicamento</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Código</label>
                        <input type="text" id="codigoMedicamentou" name="codigoMedicamentou" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="nombreu" name="nombreu" class="form-control" required maxlength="100">
                        <small id="error-nombreu" class="text-danger d-none">El nombre no puede iniciar con un número.</small>
                    </div>
                    <div class="form-group">
                        <label>Descripción <span class="text-danger">*</span></label>
                        <input type="text" id="descripcionu" name="descripcionu" class="form-control" required maxlength="500">
                    </div>
                    <div class="form-group">
                        <label>Stock <span class="text-danger">*</span></label>
                        <input type="number" id="stocku" name="stocku" class="form-control" min="0" required>
                    </div>
                    <div class="form-group">
                        <label>Fecha de Vencimiento <span class="text-danger">*</span></label>
                        <input type="date" id="fechaVencimientou" name="fechaVencimientou" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
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
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>

<script>
$(document).ready(function(){

    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    
    const hoy = new Date().toISOString().split('T')[0];
    $('input[name="fechaVencimiento"]').attr('min', hoy);
    $('input[name="fechaVencimientou"]').attr('min', hoy);

    // Funciones de modales
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

    // Validar que nombre no inicie con número en Crear
    $('#nombre').on('input', function () {
        const val = $(this).val();
        if (/^\d/.test(val)) {
            $(this).addClass('is-invalid');
            $('#error-nombre').removeClass('d-none');
        } else {
            $(this).removeClass('is-invalid');
            $('#error-nombre').addClass('d-none');
        }
    });

    // Validar que nombre no inicie con número en Editar
    $('#nombreu').on('input', function () {
        const val = $(this).val();
        if (/^\d/.test(val)) {
            $(this).addClass('is-invalid');
            $('#error-nombreu').removeClass('d-none');
        } else {
            $(this).removeClass('is-invalid');
            $('#error-nombreu').addClass('d-none');
        }
    });

    
    $('#formCrearMedicamento').submit(function(e){
        const nombreVal = $('#nombre').val();
        if (/^\d/.test(nombreVal)) {
            e.preventDefault();
            $('#nombre').addClass('is-invalid');
            $('#error-nombre').removeClass('d-none');
            mostrarError('Error de validación', 'El nombre no puede iniciar con un número.');
            return false;
        }

        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Registrando...');

        $.ajax({
            url: '/admin/medicamentos', 
            method: 'POST',
            data: $(this).serialize(),
            success: function(response){
                console.log('Medicamento creado:', response);
                $('#modalCrear').modal('hide');
                mostrarExito('Medicamento registrado', 'El medicamento fue registrado correctamente.');
                
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr){
                console.error('Error creando medicamento:', xhr);
                console.error('Response:', xhr.responseText);
                const mensajeError = procesarErroresValidacion(xhr);
                mostrarError('Error de validación', mensajeError);
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

   
    $('#formEditarMedicamento').submit(function(e){
        const nombreVal = $('#nombreu').val();
        if (/^\d/.test(nombreVal)) {
            e.preventDefault();
            $('#nombreu').addClass('is-invalid');
            $('#error-nombreu').removeClass('d-none');
            mostrarError('Error de validación', 'El nombre no puede iniciar con un número.');
            return false;
        }

        e.preventDefault();

        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Guardando...');

        $.ajax({
            url: '/admin/medicamentos', 
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response){
                console.log('Medicamento actualizado:', response);
                $('#mEditarMedicamento').modal('hide');
                mostrarExito('Medicamento actualizado', 'Los cambios fueron guardados correctamente.');
                
                setTimeout(function() {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr){
                console.error('Error actualizando medicamento:', xhr);
                console.error('Response:', xhr.responseText);
                const mensajeError = procesarErroresValidacion(xhr);
                mostrarError('Error de validación', mensajeError);
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Event listeners para editar medicamento
    $(document).on('click', '.ejecutar', function() {
        $('#codigoMedicamentou').val($(this).data('codigomed'));
        $('#nombreu').val($(this).data('nombre'));
        $('#descripcionu').val($(this).data('descripcion'));
        $('#stocku').val($(this).data('stock'));
        $('#fechaVencimientou').val($(this).data('fechav'));

        // Limpiar validaciones al cargar datos
        $('#nombreu').removeClass('is-invalid');
        $('#error-nombreu').addClass('d-none');
    });

    // Función para confirmar cambio de estado
    window.confirmarCambioEstado = function(codigo, accion) {
        if (confirm(`¿Estás seguro que deseas ${accion} este medicamento?`)) {
            document.getElementById(`estado-form-${codigo}`).submit();
        }
    };

    // Limpiar campos al cerrar modal
    $('#modalCrear').on('hidden.bs.modal', function () {
        $('#formCrearMedicamento')[0].reset();
        $('#formCrearMedicamento .form-control').removeClass('is-invalid');
        $('#error-nombre').addClass('d-none');
    });

    $('#mEditarMedicamento').on('hidden.bs.modal', function () {
        $('#formEditarMedicamento .form-control').removeClass('is-invalid');
        $('#error-nombreu').addClass('d-none');
    });

});
</script>

@vite('resources/js/app.js')

@stop