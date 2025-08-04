<?php


    /**
     * Vista Blade para la gestión de usuarios en el sistema hospitalario.
     *
     * Funcionalidades principales:
     * - Listado de usuarios registrados con paginación y acciones.
     * - Botón para crear un nuevo usuario (abre modal).
     * - Exportación de usuarios a PDF.
     * - Edición de usuario mediante modal con formulario precargado.
     * - Asignación de roles a usuarios mediante modal con checkboxes.
     * - Activación/desactivación de usuarios con confirmación.
     *
     * Estructura:
     * - Tabla con los datos principales de cada usuario: ID, nombre completo, email, roles, fecha de creación y acciones.
     * - Modal para crear usuario con validación y campos requeridos.
     * - Modal para editar usuario con campos editables y validación.
     * - Modal para asignar roles, mostrando todos los roles disponibles y los asignados.
     *
     * Variables utilizadas:
     * - $usuarios: Colección de usuarios a mostrar.
     * - $roles: Colección de roles disponibles para asignar.
     *
     * Requiere:
     * - Rutas definidas para crear, editar, asignar roles, cambiar estado y exportar PDF.
     * - Uso de métodos y helpers de Laravel para manejo de roles y validaciones.
     *
     * Notas:
     * - Utiliza Bootstrap para estilos y modales.
     * - Incluye validaciones y mensajes de error para los formularios.
     */
?>

@extends('adminlte::page')

@section('title', 'Usuarios')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />




@stop

@section('content_header')


    <h1><span class="font-weight-bold">Usuarios registrados</span></h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearUsuario">
            <i class="fas fa-user-plus"></i> Crear Usuario
        </button>


        <a href="{{ route('usuarios.pdf') }}" class="btn btn-secondary">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
    </div>


    <div class="card-body table-responsive">
        <table id="pagination-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Rol(es)</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->codigoUsuario }}</td>
                    <td>{{ ucwords(strtolower($usuario->nombreCompleto)) }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->getRoleNames()->implode(', ') }}</td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm EditarUsuario"
    data-toggle="modal" data-target="#modalEditarUsuario"
    data-codigousu="{{ $usuario->codigoUsuario }}"
    data-nombre="{{ ucwords(strtolower($usuario->nombreCompleto)) }}"
    data-email="{{ $usuario->email }}"
    data-identidad="{{ $usuario->identidad }}"
    data-fechanac="{{ $usuario->fechaNacimiento }}"
    data-telefono="{{ $usuario->telefono }}">
    <i class="fas fa-edit"></i> Editar
</button>
                        <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalAsignar{{ $usuario->codigoUsuario }}">
                            <i class="fas fa-user-cog"></i> Asignar roles
                        </button>
                        <form id="estado-form-{{ $usuario->codigoUsuario }}" 
                              action="{{ route('usuarios.cambiarEstado', $usuario->codigoUsuario) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="button" 
                                    class="btn btn-sm {{ $usuario->is_active ? 'btn-danger' : 'btn-success' }}" 
                                    onclick="confirmarCambioEstado({{ $usuario->codigoUsuario }}, '{{ $usuario->is_active ? 'desactivar' : 'activar' }}')">
                                <i class="fas {{ $usuario->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                {{ $usuario->is_active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@foreach ($usuarios as $usuario)
<div class="modal fade" id="modalAsignar{{ $usuario->codigoUsuario }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $usuario->codigoUsuario }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <form action="{{ route('usuarios.asignarRol', $usuario->codigoUsuario) }}" method="POST" class="modal-content form-asignar-roles">
            @csrf
            @method('PUT')
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title m-0">Asignar roles a {{ ucwords(strtolower($usuario->nombreCompleto)) }}</h5>
                </button>
            </div>
            <div class="modal-body">
                <fieldset class="form-group">
                     <legend>Roles disponibles:</legend>
                    @foreach ($roles as $rol)
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="roles[]"
                                   value="{{ $rol->name }}"
                                   id="rol_{{ $usuario->codigoUsuario }}_{{ $rol->name }}"
                                   {{ $usuario->hasRole($rol->name) ? 'checked' : '' }}>
                            <label class="form-check-label" for="rol_{{ $usuario->codigoUsuario }}_{{ $rol->name }}">
                                {{ ucfirst($rol->name) }}
                            </label>
                        </div>
                    @endforeach
                </fieldset>
                <div class="text-danger d-none validation-error">
                    Debes seleccionar al menos un rol.
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Guardar</button>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>
@endforeach


<div class="modal fade" id="modalCrearUsuario" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('usuarios.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title m-0">Crear Usuario</h5>
            </div>
            <div class="modal-body">
          
                <div class="form-group">
                    <label>Nombre completo</label>
                    <input type="text" name="nombreCompleto" class="form-control" value="{{ old('nombreCompleto') }}" required>
                    @error('nombreCompleto')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

           
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                
                <div class="form-group">
                    <label>Identidad</label>
                    <input type="text" name="identidad" class="form-control" value="{{ old('identidad') }}" required>
                    @error('identidad')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

               
                <div class="form-group">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="fechaNacimiento" class="form-control" value="{{ old('fechaNacimiento') }}" required>
                    @error('fechaNacimiento')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

               
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
                    @error('telefono')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

               
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
<div class="form-group">
    <label>Confirmar Contraseña</label>
    <input type="password" name="password_confirmation" class="form-control" required>
</div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Crear</button>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEditarUsuario" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
     <form id="formEditarUsuario" method="POST" class="modal-content" action="">
    @csrf
    @method('PUT')
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title m-0">Editar Usuario</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" name="codigoUsuario" id="codigoUsuario">
<div class="form-group">
    <label>Nombre completo</label>
    <input type="text" name="nombreCompleto" id="nombreCompleto" class="form-control" value="{{ old('nombreCompleto') }}" required>
    @error('nombreCompleto')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label>Email</label>
    <input type="text" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
    @error('email')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label>Identidad</label>
    <input type="text" name="identidad" id="identidad" class="form-control" value="{{ old('identidad') }}" required>
    @error('identidad')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label>Fecha de nacimiento</label>
    <input type="date" name="fechaNacimiento" id="fechaNacimiento" class="form-control" value="{{ old('fechaNacimiento') }}" required>
    @error('fechaNacimiento')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label>Teléfono</label>
    <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" required>
    @error('telefono')
        <small class="text-danger">{{ $message }}</small>
    @enderror
      </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="submit">Guardar cambios</button>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>


@stop



{{-- 
    ============================================================================
    Sección JavaScript para la vista de índice de Usuarios
    ============================================================================

    Esta sección incluye y gestiona la lógica JavaScript necesaria para la vista 
    de listado de usuarios en el sistema. A continuación se detallan sus 
    funcionalidades principales:

    1. Inclusión de librerías externas:
       - Simple Datatables: Para la gestión y visualización de tablas dinámicas.
       - jQuery: Para facilitar la manipulación del DOM y eventos.
       - Bootstrap: Para el manejo de modales y componentes visuales.
       - Vite asset: Para la integración de recursos compilados.

    2. Validación de formularios:
       - Se asegura que, al enviar formularios con la clase 'form-asignar-roles', 
         se seleccione al menos un rol, evitando envíos incompletos.

    3. Población dinámica de modales:
       - Al hacer clic en el botón '.EditarUsuario', se llenan los campos del 
         modal de edición con los datos correspondientes del usuario seleccionado.

    4. Manejo automático de errores:
       - Si existen errores de validación, se muestra automáticamente el modal 
         correspondiente ('modalEditarUsuario' o 'modalCrearUsuario') para 
         facilitar la corrección por parte del usuario.

    5. Lógica de reseteo y limpieza de modales:
       - Al mostrar u ocultar los modales, se limpian los campos y mensajes de 
         validación, eliminando clases inválidas y mensajes de error para evitar 
         interferencias visuales o funcionales.

    NOTA: Este bloque de documentación no interfiere con el código y está 
    diseñado para facilitar la comprensión y mantenimiento de la lógica JavaScript 
    implementada en esta sección de la vista.
--}}

@section('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@vite('resources/js/app.js') 



<script>
    document.querySelectorAll('.form-asignar-roles').forEach(form => {
        form.addEventListener('submit', function (e) {
            const checkboxes = form.querySelectorAll('input[name="roles[]"]');
            const errorMessage = form.querySelector('.validation-error');
            const container = form.querySelector('.roles-container');

            const algunoMarcado = Array.from(checkboxes).some(checkbox => checkbox.checked);

            if (!algunoMarcado) {
            e.preventDefault();
            errorMessage.classList.remove('d-none');
        } else {
            errorMessage.classList.add('d-none');
        }
    });
    });


  $(document).on('click', '.EditarUsuario', function () {
    const codigoUsuario = $(this).data('codigousu');

    $('#formEditarUsuario').attr('action', `/usuarios/${codigoUsuario}`);

        console.log('Action URL:', $('#formEditarUsuario').attr('action'));
    $('#codigoUsuario').val(codigoUsuario);
    $('#nombreCompleto').val($(this).data('nombre'));
    $('#email').val($(this).data('email'));
    $('#identidad').val($(this).data('identidad'));
    $('#fechaNacimiento').val($(this).data('fechanac'));
    $('#telefono').val($(this).data('telefono'));
});


         
@if ($errors->any())
    @if (old('codigoUsuario'))
        $('#modalEditarUsuario').modal('show');
    @else
        $('#modalCrearUsuario').modal('show');
    @endif
@endif

$('#modalCrearUsuario').on('show.bs.modal', function () {
      $(this).find('input:not([type=hidden])').val('');
    $(this).find('small.text-danger').remove();
});

$('#modalEditarUsuario').on('show.bs.modal', function () {
     $(this).find('input:not([type=hidden])').val('');
    $(this).find('small.text-danger').remove();
});

$('#modalEditarUsuario').on('hidden.bs.modal', function () {
    $(this).find('.text-danger').remove();
    $(this).find('.form-control').removeClass('is-invalid');
    $(this).find('form')[0].reset();
});

$('.modal').on('show.bs.modal', function () {
    $(this).find('.text-danger').remove();
    $(this).find('.form-control').removeClass('is-invalid');
});
</script>



@stop