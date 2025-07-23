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

    public function update(Request $request, medicamento $medicamento)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'stock' => 'required|integer|min:0',
            'fechaVencimiento' => 'required|date',
        ]);

        $medicamento->nombre = $request->nombre;
        $medicamento->descripcion = $request->descripcion;
        $medicamento->stock = $request->stock;
        $medicamento->fechaVencimiento = $request->fechaVencimiento;
        $medicamento->save();

        return redirect('/admin/medicamentos');
    }

    public function destroy(medicamento $medicamento)
    {
        $datosMedicamentos = medicamento::find($codigoMedicamento);
        $medicamento->delete();
        return redirect('/admin/medicamentos');
    }
}
