@extends('adminlte::page')

@section('title', 'Inicio')


@section('content')
    <h1>
    Bienvenido, 
    @if(Auth::user()->hasRole('doctor'))
        Doctor(a)
    @endif
    {{ ucwords(strtolower(Auth::user()->nombreCompleto)) }}
    </h1>

@if(Auth::user()->can('gestionar usuarios'))
    <p class="text-muted">Este es su panel como Administrador.</p>

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

        @isset($totalMedicamentos)
            <div class="col-md-6">
                <x-adminlte-small-box title="Medicamentos activos" text="{{ $totalMedicamentos }}" icon="fas fa-pills" theme="teal"/>
            </div>
        @endisset

        @isset($doctoresDisponibles)
            <div class="col-md-6">
                <x-adminlte-small-box 
                    title="Doctores Activos" 
                    text="{{ $doctoresDisponibles > 0 ? $doctoresDisponibles : 'No hay doctores activos' }}" 
                    icon="fas fa-user-check" 
                    theme="primary"
                />
            </div>
        @endisset
    </div>

    @if(isset($medicamentosBajoStock) && $medicamentosBajoStock->count() > 0)
        <x-adminlte-card title="Medicamentos con stock bajo" theme="danger" icon="fas fa-exclamation-triangle">
            <ul>
                @foreach($medicamentosBajoStock as $med)
                    <li>{{ $med->nombre }} - Stock: {{ $med->stock }}</li>
                @endforeach
            </ul>
        </x-adminlte-card>
    @endif

{{-- dashboard del doctor --}}
@elseif(Auth::user()->can('ver pacientes asignados'))
    <p class="text-muted">Este es su panel de control clínico.</p>
    <div class="row">
        {{-- Citas para hoy --}}
        <div class="col-md-3">
            <div class="info-box bg-info">
                <span class="info-box-icon bg-info"><i class="fas fa-calendar-day"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Citas para hoy</span>
                    <span class="info-box-number">{{ $citasHoy ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Pacientes asignados --}}
        <div class="col-md-3">
            <div class="info-box bg-success">
                <span class="info-box-icon bg-success"><i class="fas fa-user-injured"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pacientes asignados</span>
                    <span class="info-box-number">{{ $pacientesAsignados ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Consultas finalizadas --}}
        <div class="col-md-3">
            <div class="info-box bg-warning">
                <span class="info-box-icon bg-warning"><i class="fas fa-stethoscope"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Consultas finalizadas</span>
                    <span class="info-box-number">{{ $consultasFinalizadas ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Citas pendientes --}}
        <div class="col-md-3">
            <div class="info-box bg-danger">
                <span class="info-box-icon bg-danger"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Citas pendientes</span>
                    <span class="info-box-number">{{ $citasPendientes ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Próxima cita de hoy --}}
    @if($tieneProximaCita ?? false) 
        <div class="info-box bg-teal">
            <span class="info-box-icon"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Próxima Cita de Hoy</span>
                <span class="info-box-number">{{ $horaProximaCita ?? '--:--' }}</span>
                <span class="progress-description">
                    Paciente: {{ $nombrePaciente ?? 'Sin nombre' }} | Estado: {{ $estadoCita ?? 'Sin estado' }}
                </span>
            </div>
        </div>
    @else
        <div class="info-box bg-secondary">
            <span class="info-box-icon"><i class="fas fa-calendar-times"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Sin próximas citas</span>
                <span class="info-box-number">—</span>
                <span class="progress-description">
                    No hay citas agendadas para hoy
                </span>
            </div>
        </div>
    @endif

{{-- dashboard del paciente --}}
@elseif(Auth::user()->can('ver historial clinico propio'))
    
    <div class="row">
    <div class="col-md-6 d-flex">
        <div class="info-box bg-success w-100">
            <span class="info-box-icon bg-success"><i class="far fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Próxima cita agendada</span>
                <span class="info-box-number">{{ $proximaCita ?? 'No hay citas agendadas' }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6 d-flex">
        <div class="info-box bg-primary w-100">
            <span class="info-box-icon"><i class="far fa-calendar-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Última consulta</span>
                <span class="info-box-number" style="font-size: 14px; line-height: 1.3;">
                    Fecha de la ultima consulta: {{ $fechaUltimaConsulta ?? 'Sin consultas previas' }}<br>
                    Especialidad: {{ ucwords(strtolower($especialidad ?? 'No disponible')) }}<br>
                    Doctor: {{ ucwords(strtolower($doctor ?? 'No disponible')) }}
                </span>
            </div>
        </div>
    </div>
</div>

    <div class="callout callout-danger">
        <h5><i class="fas fa-exclamation-triangle"></i> Recordatorio</h5>
        Por favor, asegúrate de asistir puntualmente a tu cita o cancelar con anticipación.
    </div>

    <div class="info-box bg-warning">
    <span class="info-box-icon"><i class="fas fa-heartbeat"></i></span>
    <div class="info-box-content">
        <span class="info-box-text">Consejo de salud</span>
        <span class="info-box-number" style="font-size: 14px; line-height: 1.4;">
            Toma al menos 8 vasos de agua al día.<br>
            Realiza actividad física moderada 30 minutos al día.
        </span>
    </div>
</div>



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