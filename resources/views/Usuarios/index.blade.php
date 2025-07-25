@extends('adminlte::page')

@section('title', 'Usuarios')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" />
@stop

@section('content_header')
    <h1>Usuarios registrados</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Crear Usuario
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
                    <td>{{ $usuario->nombreCompleto }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->getRoleNames()->implode(', ') }}</td>
                    <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="" class="btn btn-sm btn-warning" ><i class="fas fa-edit"></i> Editar
                         </a>
                        <button data-modal-target="modal-asignar-" data-modal-toggle="modal-asignar-" class="btn btn-sm btn-info">
    <i class="fas fa-user-cog"></i> Asignar roles
</button>
                         <form id="estado-form-{{ $usuario->codigoUsuario }}" action="{{ route('usuarios.cambiarEstado', $usuario->codigoUsuario) }}" method="POST" style="display:inline;">
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
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
@vite('resources/js/app.js')
@stop