@extends('adminlte::page')

@section('title', 'Inicio')


@section('content')
    <h1>
    Bienvenido, 
    @if(Auth::user()->hasRole('doctor'))
        Doctor(a)
    @endif
    {{ Auth::user()->nombreCompleto }}
</h1>


    @if(Auth::user()->can('gestionar usuarios'))
        <h2>"dashboard del admin"</h2>
        
        {{-- Luego probar el componente --}}
        <div class="row">
    @isset($totalPacientes)
        <div class="col-md-6">
            <x-adminlte-small-box title="Pacientes" text="{{ $totalPacientes }}" icon="fas fa-users" theme="info"/>
        </div>
    @endisset

    @isset($totalDoctores)
        <div class="col-md-6">
            <x-adminlte-small-box title="Doctores" text="{{ $totalDoctores }}" icon="fas fa-user-md" theme="success"/>
        </div>
    @endisset
</div>


    @elseif(Auth::user()->can('ver pacientes asignados'))
        <h2>"dashboard del doctor"</h2>
    @elseif(Auth::user()->can('ver historial clinico propio'))
        <h2>"dashboard del paciente"</h2>
    @else
        <p>No tienes permisos para ver un dashboard.</p>
    @endif
@endsection


@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop