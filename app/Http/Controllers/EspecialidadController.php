<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    // Mostrar todas las especialidades
    public function index()
    {
        $especialidades = Especialidad::all();
        return view('especialidades.index', compact('especialidades'));
    }

    // Guardar nueva especialidad
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        Especialidad::create([
            'nombre' => strtoupper($request->nombre),  // Guardar en mayúsculas
        ]);

        return redirect()->route('especialidades.index')->with('success', 'Especialidad creada exitosamente.');
    }

    // Actualizar especialidad existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $especialidad = Especialidad::findOrFail($id);
        $especialidad->nombre = strtoupper($request->nombre); // Guardar en mayúsculas
        $especialidad->save();

        return redirect()->route('especialidades.index')->with('success', 'Especialidad actualizada exitosamente.');
    }
}
