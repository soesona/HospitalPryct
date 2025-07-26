<?php

namespace App\Http\Controllers;

use App\Models\medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends Controller
{
    public function index()
    {
        $datosMedicamentos = medicamento::all();
        return view('medicamento.index')->with('listaMedicamentos', $datosMedicamentos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'stock' => 'required|integer|min:0',
            'fechaVencimiento' => 'required|date',
        ]);

        $nuevo = new medicamento();
        $nuevo->nombre = $request->nombre;
        $nuevo->descripcion = $request->descripcion;
        $nuevo->stock = $request->stock;
        $nuevo->fechaVencimiento = $request->fechaVencimiento;
        $nuevo->save();

        return redirect('/admin/medicamentos');
    }

    public function update(Request $request)
    {
    $request->validate([
        'codigoMedicamentou' => 'required|exists:medicamentos,codigoMedicamento',
        'nombreu' => 'required|string|max:100',
        'descripcionu' => 'required|string',
        'stocku' => 'required|integer|min:0',
        'fechaVencimientou' => 'required|date',
    ]);

    $medicamento = medicamento::find($request->get('codigoMedicamentou'));

    $medicamento->nombre = $request->get('nombreu');
    $medicamento->descripcion = $request->get('descripcionu');
    $medicamento->stock = $request->get('stocku');
    $medicamento->fechaVencimiento = $request->get('fechaVencimientou');
    $medicamento->save();

    return redirect('/admin/medicamentos');
    }

    public function cambiarEstado($codigoMedicamento)
    {
    $datosMedicamentos = medicamento::findOrFail($codigoMedicamento);
    $datosMedicamentos->activo = !$datosMedicamentos->activo;
    $datosMedicamentos->save();

    return redirect('/admin/medicamentos')->with('success', 'Estado del medicamento actualizado correctamente.');
    }

    
}
