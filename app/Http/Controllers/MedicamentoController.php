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

    // ✅ FUNCIÓN CORREGIDA - store 
    public function store(Request $request)
    {
        try {
            $request->validate([
                    'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ][A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-.,]*$/',
                    'unique:medicamentos,nombre',
                    function ($attribute, $value, $fail) {
                        $existe = medicamento::whereRaw('LOWER(nombre) = ?', [strtolower(trim($value))])->exists();
                        
                        if ($existe) {
                            $fail('Ya existe un medicamento con este nombre.');
                        }
                    }
                ],
                'descripcion' => 'required|string|max:500',
                'stock' => 'required|integer|min:0',
                'fechaVencimiento' => 'required|date|after:today',
            ]);

            $nuevo = new medicamento();
            $nuevo->nombre = strtoupper(trim($request->nombre));
            $nuevo->descripcion = strtoupper(trim($request->descripcion));
            $nuevo->stock = $request->stock;
            $nuevo->fechaVencimiento = $request->fechaVencimiento;
            $nuevo->activo = true;
            $nuevo->save();

            return response()->json([
                'success' => true,
                'message' => 'Medicamento registrado correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ FUNCIÓN CORREGIDA - update usando PUT /admin/medicamentos
    public function update(Request $request)
    {
        try {
            $request->validate([
                'codigoMedicamentou' => 'required|exists:medicamentos,codigoMedicamento',
                    'nombreu' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ][A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s\-.,]*$/',
                    function ($attribute, $value, $fail) use ($request) {
                        $existe = medicamento::whereRaw('LOWER(nombre) = ?', [strtolower(trim($value))])
                                            ->where('codigoMedicamento', '!=', $request->codigoMedicamentou)
                                            ->exists();
                        
                        if ($existe) {
                            $fail('Ya existe otro medicamento con este nombre.');
                        }
                    }
                ],
                'descripcionu' => 'required|string|max:500',
                'stocku' => 'required|integer|min:0',
                'fechaVencimientou' => 'required|date',
            ]);

            $medicamento = medicamento::findOrFail($request->codigoMedicamentou);

            $medicamento->nombre = strtoupper(trim($request->nombreu));
            $medicamento->descripcion = strtoupper(trim($request->descripcionu));
            $medicamento->stock = $request->stocku;
            $medicamento->fechaVencimiento = $request->fechaVencimientou;
            $medicamento->save();

            return response()->json([
                'success' => true,
                'message' => 'Medicamento actualizado correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error interno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cambiarEstado($codigoMedicamento)
    {
        try {
            $medicamento = medicamento::findOrFail($codigoMedicamento);
            $medicamento->activo = !$medicamento->activo;
            $medicamento->save();

            return redirect('/admin/medicamentos')->with('success', 'Estado del medicamento actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect('/admin/medicamentos')->with('error', 'Error al cambiar el estado del medicamento.');
        }
    }
}