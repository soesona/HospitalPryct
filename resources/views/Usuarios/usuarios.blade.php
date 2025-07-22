@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Usuarios registrados</h1>
@stop

@section('content')
   <div class="card">
    <div class="card-header">
   <a href="" class="btn btn-primary"> <i class="fas fa-user-plus"></i> Crear Usuario
</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ ucfirst($usuario->rol) }}</td>
                        <td>{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{" class="btn btn-sm btn-warning">Editar</a>
                         
                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
            </form>
        </td>
    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop