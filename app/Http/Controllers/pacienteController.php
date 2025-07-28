<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;

class pacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $datosPacientes = Paciente::with('usuario')->get();
        return view('pacientes.index')->with('listaPacientes', $datosPacientes);
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
        $request->validate([
        'codigoUsuario' => 'required|exists:users,codigoUsuario',
        ]);

        $paciente = new Paciente();
        $paciente->codigoUsuario = $request->codigoUsuario;
        $paciente->save();

        return redirect('/pacientes')->with('success', 'Paciente registrado correctamente.');
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
