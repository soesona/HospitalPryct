<?php
/**
 * Controlador para la gestión de medicamentos.
 *
 * Este controlador maneja las operaciones CRUD para los medicamentos,
 * incluyendo la validación de datos, el registro, actualización, cambio de estado
 * y exportación de reportes en PDF.
 *
 * Métodos:
 * - index(): Muestra la lista de medicamentos.
 * - store(Request $request): Registra un nuevo medicamento con validación personalizada.
 * - update(Request $request): Actualiza los datos de un medicamento existente con validación personalizada.
 * - cambiarEstado($codigoMedicamento): Cambia el estado (activo/inactivo) de un medicamento.
 * - exportarPDF(): Exporta la lista de medicamentos a un archivo PDF.
 *
 * Validaciones:
 * - El nombre del medicamento debe ser único (ignorando mayúsculas/minúsculas y espacios).
 * - El nombre y la descripción tienen restricciones de formato y longitud.
 * - El stock debe ser un número entero no negativo.
 * - La fecha de vencimiento debe ser posterior a la fecha actual (en registro).
 *
 * Manejo de errores:
 * - Devuelve respuestas JSON con mensajes de éxito o error en los métodos store y update.
 * - Redirecciona con mensajes flash en el cambio de estado.
 *
 * Dependencias:
 * - App\Models\medicamento
 * - Illuminate\Http\Request
 * - \PDF (para exportación en PDF)
 */


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

    public function exportarPDF()
    {
        $medicamentos = medicamento::all();
        $pdf = \PDF::loadView('reportes.medicamentosreportes', compact('medicamentos'));
        return $pdf->download('reporte_medicamentos.pdf');
    }
}