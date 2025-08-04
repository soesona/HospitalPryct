<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Medicamento;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
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
   /*
    CUANDO ESTÉ TODO FUNCIONANDO SE EDITARÁ PARA QUE EL DASBOARD QUEDE FUNCIONAL

    if (Auth::user()->hasRole('paciente') && Auth::user()->roles->count() === 1) {
        $paciente = Auth::user()->paciente;
        if ($paciente) {
            $codigoPaciente = $paciente->codigoPaciente;

            // Último historial clínico con consulta y doctor
            $ultimoHistorial = \App\Models\HistorialClinico::where('codigoPaciente', $codigoPaciente)
                ->with(['consulta.doctor'])
                ->orderByDesc('fechaRegistro')
                ->first();

            $data['medicamentoRecetado'] = 'Sin medicamentos registrados';
            $data['fechaUltimaConsulta'] = 'Sin consultas previas';
            $data['especialidad'] = 'No disponible';
            $data['doctor'] = 'No disponible';

            if ($ultimoHistorial && $ultimoHistorial->consulta) {
                $consulta = $ultimoHistorial->consulta;
                $data['fechaUltimaConsulta'] = $ultimoHistorial->fechaRegistro->format('d M Y');
                $data['doctor'] = $consulta->doctor->nombre ?? 'Sin nombre';
                $data['especialidad'] = $consulta->doctor->especialidad ?? 'Sin especialidad';

                $consultaMedicamento = \App\Models\ConsultaMedicamento::where('codigoConsulta', $consulta->codigoConsulta)
                    ->with('medicamento')
                    ->first();

                if ($consultaMedicamento && $consultaMedicamento->medicamento) {
                    $data['medicamentoRecetado'] = $consultaMedicamento->medicamento->nombre;
                }
            }  

            // Próxima cita agendada
            $proximaCita = \App\Models\Cita::where('codigoPaciente', $codigoPaciente)
                ->whereDate('fechaCita', '>=', now()->toDateString())
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->orderBy('fechaCita')
                ->orderBy('horaInicio')
                ->first();

            $data['proximaCita'] = 'No hay citas agendadas';
            if ($proximaCita) {
                $data['proximaCita'] = $proximaCita->fechaCita->format('d M Y') . ' – ' . date('h:i A', strtotime($proximaCita->horaInicio));
            }
        } else {
            $data['medicamentoRecetado'] = 'Sin medicamentos registrados';
            $data['fechaUltimaConsulta'] = 'Sin consultas previas';
            $data['especialidad'] = 'No disponible';
            $data['doctor'] = 'No disponible';
            $data['proximaCita'] = 'No hay citas agendadas';
        }
    }

*/
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
