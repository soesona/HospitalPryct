@extends('adminlte::page')

@section('title', 'Usuarios')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

@stop

@section('content_header')
    <h1>Usuarios registrados</h1>
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
                         data-nombre="{{ $usuario->nombreCompleto }}"
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
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
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
      <form id="formEditarUsuario" method="POST" class="modal-content">
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
    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
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




@section('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

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


 @vite('resources/js/app.js') 
@stop