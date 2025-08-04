<?php

/**
 * Controlador para gestionar las enfermedades en la aplicación.
 *
 * Métodos:
 * - index(): Muestra una lista de todas las enfermedades.
 * - store(Request $request): Almacena una nueva enfermedad después de validar los datos recibidos.
 * - update(Request $request, string $id): Actualiza una enfermedad existente con validaciones personalizadas.
 * - exportarPDF(): Exporta la lista de enfermedades a un archivo PDF.
 *
 * Validaciones personalizadas:
 * - El nombre de la enfermedad es obligatorio, máximo 100 caracteres, formato específico y no debe duplicarse (ignorando mayúsculas/minúsculas).
 * - El nombre se almacena siempre en mayúsculas.
 *
 * @package App\Http\Controllers
 */
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
        $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:100',
            // Validación personalizada: verifica duplicados ignorando mayúsculas
            function ($attribute, $value, $fail) {
                if (\App\Models\Enfermedad::where('nombre', strtoupper($value))->exists()) {
                    $fail('Esta enfermedad ya existe.');
                }
            },
            // Validación del formato (sin guiones al inicio/final ni dobles)
           
           'regex:/^(?![\d-])[A-Za-zÀ-ÿ0-9]+(?:[ -]?[A-Za-zÀ-ÿ0-9]+)*$/u'

        ],
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'Máximo 100 caracteres.',
        'nombre.regex' => 'Formato inválido. No inicie con número o guion, ni use guiones dobles o al final.',
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
          $enfermedad = Enfermedad::findOrFail($id);

    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:100',
            function ($attribute, $value, $fail) use ($id) {
                $nombreMayus = strtoupper($value);
                if (\App\Models\Enfermedad::where('nombre', $nombreMayus)
                    ->where('codigoEnfermedad', '!=', $id)
                    ->exists()) {
                    $fail('Esta enfermedad ya existe.');
                }
            },
           'regex:/^(?![\d-])[A-Za-zÀ-ÿ0-9]+(?:[ -]?[A-Za-zÀ-ÿ0-9]+)*$/u'

        ],
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'Máximo 100 caracteres.',
        'nombre.regex' => 'Formato inválido. No inicie con número o guion, ni use guiones dobles o al final.',
    ]);

    $enfermedad->update([
        'nombre' => strtoupper($request->nombre),
    ]);

    return redirect()->back()->withInput(); 
    }
 public function exportarPDF()
    {
        $enfermedades = Enfermedad::all();
        $pdf = \PDF::loadView('reportes.enfermedadesreportes', compact('enfermedades'));
        return $pdf->download('reporte_enfermedades.pdf');
    }

   
}