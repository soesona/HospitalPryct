<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistorialesClinicos;
use App\Models\Paciente;
use App\Models\Consulta;
use App\Models\Doctor;
use App\Models\Enfermedad;
use Illuminate\Support\Facades\Auth;

class HistorialesClinicosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $modo = $request->query('modo');
    $usuario = Auth::user();
    $historiales = collect();

    switch ($modo) {
        case 'paciente':
            $paciente = Paciente::where('codigoUsuario', $usuario->codigoUsuario)->first();

            if (!$paciente) {
                abort(403, 'No tienes un perfil de paciente.');
            }

            $historiales = HistorialesClinicos::with(['consulta.doctor.user', 'paciente.usuario'])
                ->where('codigoPaciente', $paciente->codigoPaciente)
                ->paginate(10);
            break;

        case 'doctor':
            $doctor = Doctor::where('codigoUsuario', $usuario->codigoUsuario)->first();

            if (!$doctor) {
                abort(403, 'No tienes un perfil de doctor.');
            }

            $consultas = Consulta::where('codigoDoctor', $doctor->codigoDoctor)->pluck('codigoConsulta');

            $historiales = HistorialesClinicos::with(['consulta.paciente.usuario', 'consulta.doctor.user'])
                ->whereIn('codigoConsulta', $consultas)
                ->paginate(10);
            break;

        case 'admin':
            if (!$usuario->hasRole('admin')) {
                abort(403, 'Acceso denegado.');
            }

            $historiales = HistorialesClinicos::with(['consulta.paciente.usuario', 'consulta.doctor.user'])
                ->paginate(10);
            break;

        default:
            abort(400, 'Modo de historial no especificado o inválido.');
    }

    return view('historialesclinicos.index', compact('historiales', 'modo'));
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
