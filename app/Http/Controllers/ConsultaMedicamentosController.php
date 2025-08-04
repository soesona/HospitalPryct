<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsultaMedicamentos;
use App\Http\Requests\StoreConsultaMedicamentosRequest;
use App\Http\Requests\UpdateConsultaMedicamentosRequest;
use App\Models\Consulta;
use App\Models\medicamento;

class ConsultaMedicamentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($codigoConsulta)
    {
        //
        $medicamentosAsignados = ConsultaMedicamentos::with('medicamento')
            ->where('codigoConsulta', $codigoConsulta)
            ->get();

        $todosMedicamentos = medicamento::where('activo', 1)->get();

        return view('Consultas.medicamentos.index', compact('medicamentosAsignados', 'codigoConsulta', 'todosMedicamentos'));
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
    public function store(Request $request, $codigoConsulta)
    {
        //
        $request->validate([
            'codigoMedicamento' => 'required|exists:medicamentos,codigoMedicamento',
            'cantidadEntregada' => 'required|integer|min:1',
        ]);

        $medicamento = medicamento::findOrFail($request->codigoMedicamento);

        if ($medicamento->stock < $request->cantidadEntregada) {
            return back()->withErrors(['cantidadEntregada' => 'No hay suficiente stock para asignar la cantidad solicitada.'])
                         ->withInput();
        }

        // Restar el stock y guardar
        $medicamento->stock -= $request->cantidadEntregada;
        $medicamento->save();

        // Crear la asignación
        ConsultaMedicamentos::create([
            'codigoConsulta' => $codigoConsulta,
            'codigoMedicamento' => $request->codigoMedicamento,
            'cantidadEntregada' => $request->cantidadEntregada,
        ]);

        return redirect()->route('Consultas.medicamentos.index', $codigoConsulta)
            ->with('success', 'Medicamento asignado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ConsultaMedicamentos $consultaMedicamentos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConsultaMedicamentos $consultaMedicamentos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $codigoConsulta)
    {
        //
        $request->validate([
        'cantidadEntregada' => 'required|integer|min:1',
        'codigoEntrega' => 'required|exists:consulta_medicamentos,codigoEntrega',
        ]);

        // Buscar la asignación existente
        $asignacion = ConsultaMedicamentos::findOrFail($request->codigoEntrega);
        $medicamento = medicamento::findOrFail($asignacion->codigoMedicamento);

        $cantidadNueva = $request->cantidadEntregada;
        $cantidadVieja = $asignacion->cantidadEntregada;

        $diferencia = $cantidadNueva - $cantidadVieja; // puede ser positivo o negativo

        // Validar stock si se está aumentando la cantidad asignada
        if ($diferencia > 0 && $medicamento->stock < $diferencia) {
            return back()->withErrors(['cantidadEntregada' => 'No hay suficiente stock para aumentar la cantidad.'])
                        ->withInput();
        }

        // Actualizar stock
        $medicamento->stock -= $diferencia;
        $medicamento->save();

        // Actualizar la asignación
        $asignacion->cantidadEntregada = $cantidadNueva;
        $asignacion->save();

        return redirect()->route('Consultas.medicamentos.index', $codigoConsulta)
        ->with('success', 'Medicamento actualizado correctamente.');
    }

}
