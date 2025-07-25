@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <h1>Bienvenido, {{ Auth::user()->nombreCompleto }}</h1>

    @if(Auth::user()->can('gestionar usuarios'))
        <h2>"dashboard del admin"</h2>
        {{-- Aquí ponés el dashboard del admin --}}
    
    @elseif(Auth::user()->can('ver pacientes asignados'))
        <h2>"dashboard del doctor"</h2>
        {{-- Aquí ponés el dashboard del doctor --}}
    
    @elseif(Auth::user()->can('ver historial clinico propio'))
        <h2>"dashboard del paciente"</h2>
        {{-- Aquí ponés el dashboard del paciente --}}
    
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