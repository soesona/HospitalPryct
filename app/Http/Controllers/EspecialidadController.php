<?php
/**
 * Controlador para gestionar las especialidades médicas.
 *
 * Métodos:
 * - index(): Muestra todas las especialidades.
 * - store(Request $request): Almacena una nueva especialidad con validación de duplicados y soporte para AJAX.
 * - update(Request $request, $id): Actualiza una especialidad existente con validación de duplicados y soporte para AJAX.
 * - exportarPDF(): Exporta el listado de especialidades a un archivo PDF.
 *
 * Validaciones:
 * - El nombre es requerido, debe ser una cadena de máximo 100 caracteres, solo letras y espacios.
 * - No se permiten nombres duplicados (insensible a mayúsculas/minúsculas).
 *
 * Respuestas:
 * - Soporta respuestas estándar y AJAX (JSON) para operaciones de creación y actualización.
 *
 * Excepciones:
 * - Maneja errores de validación y errores internos, retornando mensajes apropiados según el tipo de petición.
 *
 * @package App\Http\Controllers
 */


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

    //  store con validación de duplicados y respuesta AJAX
    public function store(Request $request)
    {
        try {
            $request->validate([
                    'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/u', // Permitir solo letras y espacios
                    function ($attribute, $value, $fail) {
                        // Verificar si ya existe una especialidad con el mismo nombre (case insensitive)
                        $existe = Especialidad::whereRaw('LOWER(nombre) = ?', [strtolower(trim($value))])->exists();
                        
                        if ($existe) {
                            $fail('Ya existe una especialidad con este nombre.');
                        }
                    }
                ]
            ]);

            $especialidad = Especialidad::create([
                'nombre' => strtoupper(trim($request->nombre)),
            ]);

            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Especialidad registrada correctamente',
                    'data' => $especialidad
                ]);
            }

            return redirect()->route('especialidades.index')->with('success', 'Especialidad creada exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al crear la especialidad.');
        }
    }

    
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                    'nombre' => [
                    'required',
                    'string',
                    'max:100',
                    'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/u', // Permitir solo letras y espacios
                    function ($attribute, $value, $fail) use ($id) {
                        // Verificar si ya existe otra especialidad con el mismo nombre (excluyendo la actual)
                        $existe = Especialidad::whereRaw('LOWER(nombre) = ?', [strtolower(trim($value))])
                                            ->where('codigoEspecialidad', '!=', $id)
                                            ->exists();
                        
                        if ($existe) {
                            $fail('Ya existe otra especialidad con este nombre.');
                        }
                    }
                ]
            ]);

            $especialidad = Especialidad::findOrFail($id);
            $especialidad->nombre = strtoupper(trim($request->nombre));
            $especialidad->save();

            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Especialidad actualizada correctamente',
                    'data' => $especialidad
                ]);
            }

            return redirect()->route('especialidades.index')->with('success', 'Especialidad actualizada exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error al actualizar la especialidad.');
        }
    }

    public function exportarPDF()
    {
        $especialidades = Especialidad::all();
        $pdf = \PDF::loadView('reportes.especialidadesreportes', compact('especialidades'));
        return $pdf->download('reporte_especialidades.pdf');
    }
}