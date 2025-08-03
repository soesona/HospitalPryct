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
        return view('Enfermedades.enfermedades', compact('enfermedades')); 
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
    'nombre' => [
        'required',
        'string',
        'max:100',
        'regex:/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ](?!.*--)(?!.*-$)[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9\- ]*$/'
    ],
], [
    'nombre.regex' => 'El nombre debe comenzar con una letra, no puede contener guiones dobles ni terminar en guion.',
]);

        Enfermedad::create([
            'nombre' => strtoupper($request->nombre),  
        ]);

         return redirect()->back()->withInput(); 
    }

   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
          $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:100',
            'regex:/^[A-Za-zÁÉÍÓÚÜÑáéíóúüñ](?!.*--)(?!.*-$)[A-Za-zÁÉÍÓÚÜÑáéíóúüñ0-9\- ]*$/'
             ],
       ], [
    'nombre.regex' => 'El nombre debe comenzar con una letra, no puede contener guiones dobles ni terminar en guion.',
]);

    $enfermedad = Enfermedad::findOrFail($id);
    $enfermedad->nombre = strtoupper($request->nombre); 
    $enfermedad->save();

    return redirect('/enfermedad');
    }

   
}
