<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enfermedad; 

class EnfermedadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enfermedades = Enfermedad::all(); 
        return view('Enfermedades.index', compact('enfermedades')); 
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        Enfermedad::create([
            'nombre' => strtoupper($request->nombre),  // Guardar en mayúsculas
        ]);

        return redirect()->route('enfermedades.index')->with('success', 'Enfermedad registrada exitosamente.');
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
