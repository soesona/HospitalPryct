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
