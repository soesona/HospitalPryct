<?php

/**
 * Class ConsultasController
 *
 * Controlador para gestionar las consultas médicas en el sistema.
 * Permite listar, registrar y actualizar consultas, así como asociarlas a citas, doctores, pacientes y enfermedades.
 *
 * Métodos:
 * - index(): Muestra un listado de consultas y citas confirmadas para el doctor autenticado.
 * - store(Request $request): Almacena una nueva consulta en la base de datos, valida los datos y actualiza el estado de la cita.
 * - update(Request $request, string $id): Actualiza los datos de una consulta existente.
 *
 * @package App\Http\Controllers
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Consulta;
use App\Models\Paciente;
use App\Models\Enfermedad;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ConsultasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $usuario = Auth::user();
    $doctor = $usuario->doctor;

    if (!$doctor) {
        $consultas = collect();
        $citas = collect();
    } else {
        $consultas = Consulta::where('codigoDoctor', $doctor->codigoDoctor)->get();
        $citas = \App\Models\Cita::with('paciente')
            ->where('codigoDoctor', $doctor->codigoDoctor)
            ->where('estado', 'confirmada')
            ->orderBy('fechaCita', 'asc')
            ->get();
    }

    $enfermedades = Enfermedad::all();

    return view('consultas.index', compact('consultas', 'citas', 'enfermedades'));
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
        $usuario = Auth::user();
        $doctor = $usuario->doctor;

        $request->validate([
        'codigoCita' => 'required|exists:citas,codigoCita|unique:consultas,codigoCita',
        'codigoPaciente' => 'required|exists:pacientes,codigoPaciente',
        'codigoDoctor' => 'required|exists:doctores,codigoDoctor',
        'codigoEnfermedad' => 'required|exists:enfermedades,codigoEnfermedad',
        'diagnostico' => 'required|string',
        'observaciones' => 'nullable|string',
        ]);

        $consultas = Consulta::with([
       'paciente.usuario',
        'doctor.user',
        'enfermedad'
        ])
        ->where('codigoDoctor', $doctor->codigoDoctor)
        ->get();

        

        $consulta = new Consulta();
        $consulta->codigoCita = $request->codigoCita;
        $consulta->codigoPaciente = $request->codigoPaciente;
        $consulta->codigoDoctor = $request->codigoDoctor;
        $consulta->codigoEnfermedad = $request->codigoEnfermedad;
        $consulta->diagnostico = strtoupper($request->diagnostico);
        $consulta->observaciones = strtoupper($request->observaciones);
        $consulta->save();

        \App\Models\Cita::where('codigoCita', $request->codigoCita)
        ->update(['estado' => 'finalizada']);

        return redirect()->back()->with('success', 'Consulta registrada correctamente.');

        // Crear el historial clínico
        $enfermedad = Enfermedad::find($request->codigoEnfermedad);

        HistorialesClinicos::create([
        'codigoPaciente' => $request->codigoPaciente,
        'codigoConsulta' => $consulta->codigoConsulta,
        'fechaRegistro' => Carbon::now(),
        'descripcion' => strtoupper('Enfermedad: ' . $enfermedad->nombre . ' | Diagnóstico: ' . $consulta->diagnostico),
]);

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
        $request->validate([
            'codigoCita' => 'required|exists:citas,codigoCita|unique:consultas,codigoCita,' . $id . ',codigoConsulta',
            'codigoPaciente' => 'required|exists:pacientes,codigoPaciente',
            'codigoDoctor' => 'required|exists:doctores,codigoDoctor',
            'codigoEnfermedad' => 'required|exists:enfermedades,codigoEnfermedad',
            'diagnostico' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $consulta = Consulta::findOrFail($id);
        $consulta->codigoCita = $request->codigoCita;
        $consulta->codigoPaciente = $request->codigoPaciente;
        $consulta->codigoDoctor = $request->codigoDoctor;
        $consulta->codigoEnfermedad = $request->codigoEnfermedad;
        $consulta->diagnostico = strtoupper($request->diagnostico);
        $consulta->observaciones = strtoupper($request->observaciones);
        $consulta->save();

        return redirect()->back()->with('success', 'Consulta actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
