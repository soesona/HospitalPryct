<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Medicamento;
use Carbon\Carbon;
use App\Models\HistorialesClinicos;
use App\Models\Consulta;
use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Paciente;
use App\Models\Especialidad;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    Carbon::setLocale('es'); 
    $data = [];

    // Para admin o quien pueda gestionar usuarios
    if (Auth::user()->can('gestionar usuarios')) {
        $data['totalPacientes'] = User::role('paciente')->count();
        $data['totalDoctores'] = User::role('doctor')->count();
        $data['totalMedicamentos'] = \App\Models\medicamento::where('activo', true)->count();

        // Contar doctores activos
        $data['doctoresDisponibles'] = User::role('doctor')
        ->where('is_active', true)
        ->count();

        // Medicamentos con stock bajo (menos de 10 unidades)
        $data['medicamentosBajoStock'] = Medicamento::where('stock', '<', 10)
            ->where('activo', true)
            ->get();

    }


    // Para doctor o quien pueda ver pacientes asignados
    if (Auth::user()->can('ver pacientes asignados')) {
        // Obtener el código del doctor actual
        $doctor = Auth::user()->doctor; // Relación hasOne en User model
        $codigoDoctor = $doctor ? $doctor->codigoDoctor : null;

        if ($codigoDoctor) {
            // 1. Citas para hoy
            $data['citasHoy'] = \App\Models\Cita::where('codigoDoctor', $codigoDoctor)
                ->whereDate('fechaCita', today())
                ->whereIn('estado', [ 'confirmada'])
                ->count();

            // 2. Pacientes asignados (pacientes únicos que han tenido consulta)
            $data['pacientesAsignados'] = \App\Models\Consulta::where('codigoDoctor', $codigoDoctor)
            ->distinct('codigoPaciente')
            ->count('codigoPaciente');

            // 3. Consultas finalizadas (consultas reales registradas)
            $data['consultasFinalizadas'] = \App\Models\Consulta::where('codigoDoctor', $codigoDoctor)
                ->count();

            // 4. Citas pendientes 
            $data['citasPendientes'] = \App\Models\Cita::where('codigoDoctor', $codigoDoctor)
                ->whereIn('estado', ['pendiente'])
                ->whereDate('fechaCita', '>=', today())
                ->count();

            // 5. Próxima cita de hoy
            $proximaCitaHoy = \App\Models\Cita::where('codigoDoctor', $codigoDoctor)
                ->whereDate('fechaCita', today())
                ->whereIn('estado', [ 'confirmada'])
                ->with('paciente.usuario') // Relación paciente -> usuario
                ->orderBy('horaInicio')
                ->first();

            if ($proximaCitaHoy) {
                $data['tieneProximaCita'] = true;
                $data['horaProximaCita'] = date('h:i A', strtotime($proximaCitaHoy->horaInicio));
                $data['nombrePaciente'] = $proximaCitaHoy->paciente->usuario->nombreCompleto ?? 'Sin nombre';
                $data['estadoCita'] = ucfirst($proximaCitaHoy->estado);
            } else {
                $data['tieneProximaCita'] = false;
            }
        } else {
            // Si no se encuentra el doctor, inicializar con ceros
            $data['citasHoy'] = 0;
            $data['pacientesAsignados'] = 0;
            $data['consultasFinalizadas'] = 0;
            $data['citasPendientes'] = 0;
            $data['tieneProximaCita'] = false;
        }
    }

    // Para paciente, si tiene permiso para ver su historial clínico
   
    if (Auth::user()->hasRole('paciente') && Auth::user()->roles->count() === 1) {
    $paciente = Auth::user()->paciente;
    if ($paciente) {
        $codigoPaciente = $paciente->codigoPaciente;
        
        // Valores por defecto
        $data['fechaUltimaConsulta'] = 'Sin consultas previas';
        $data['doctor'] = 'No disponible';
        $data['especialidad'] = 'No disponible';
        
        // Buscar la última cita finalizada 
        $ultimaCita = \App\Models\Cita::where('codigoPaciente', $codigoPaciente)
            ->where('estado', 'finalizada')
            ->with('doctor.user', 'doctor.especialidad')
            ->orderByDesc('fechaCita')
            ->orderByDesc('horaInicio')
            ->first();
            
        if ($ultimaCita && $ultimaCita->doctor) {
            $data['fechaUltimaConsulta'] = Carbon::parse($ultimaCita->fechaCita)
                ->locale('es')
                ->isoFormat('D MMM YYYY');
            $data['doctor'] = $ultimaCita->doctor->user->nombreCompleto ?? 'Sin nombre';
            $data['especialidad'] = $ultimaCita->doctor->especialidad->nombre ?? 'Sin especialidad';
        }
        
        // Próxima cita
        $proximaCita = \App\Models\Cita::where('codigoPaciente', $codigoPaciente)
            ->whereDate('fechaCita', '>=', now()->toDateString())
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->orderBy('fechaCita')
            ->orderBy('horaInicio')
            ->first();
            
        $data['proximaCita'] = 'No hay citas agendadas';
        if ($proximaCita) {
            $data['proximaCita'] = Carbon::parse($proximaCita->fechaCita)
                ->locale('es')
                ->isoFormat('D MMM YYYY') . ' – ' . Carbon::parse($proximaCita->horaInicio)->format('h:i A');
        }

        // Próxima cita
        $proximaCita = \App\Models\Cita::where('codigoPaciente', $codigoPaciente)
            ->whereDate('fechaCita', '>=', now()->toDateString())
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->orderBy('fechaCita')
            ->orderBy('horaInicio')
            ->first();

        $data['proximaCita'] = 'No hay citas agendadas';
        if ($proximaCita) {
            $data['proximaCita'] = Carbon::parse($proximaCita->fechaCita)
                ->locale('es')
                ->isoFormat('D MMM YYYY') . ' – ' . Carbon::parse($proximaCita->horaInicio)->format('h:i A');
        }
    } else {
        $data['fechaUltimaConsulta'] = 'Sin consultas previas';
        $data['doctor'] = 'No disponible';
        $data['especialidad'] = 'No disponible';
        $data['proximaCita'] = 'No hay citas agendadas';
    }
}

    return view('dashboard', $data);
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
