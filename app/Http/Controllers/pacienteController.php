<?php
/**
 * Clase PacienteController
 *
 * Controlador para la gestión de recursos Paciente.
 *
 * Métodos:
 * - index(): Muestra un listado de los recursos Paciente con los datos relacionados del usuario.
 * - store(Request $request): Almacena un nuevo recurso Paciente en la base de datos.
 * - exportarPDF(): Exporta la lista de recursos Paciente con los datos relacionados del usuario en un archivo PDF.
 *
 * @package App\Http\Controllers
 */

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;

class pacienteController extends Controller
{
    /**
     * Muestra un listado de los pacientes.
     */
    public function index()
    {
        $datosPacientes = Paciente::with('usuario')->get();
        return view('pacientes.index')->with('listaPacientes', $datosPacientes);
    }

    /**
     * Almacena un nuevo paciente en la base de datos.
     */
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'edad' => 'required|integer',
            'usuario_id' => 'required|exists:users,id',
        ]);

        // Crear el paciente
        Paciente::create([
            'nombre' => $request->nombre,
            'edad' => $request->edad,
            'usuario_id' => $request->usuario_id,
        ]);

        return redirect()->route('pacientes.index')->with('success', 'Paciente creado correctamente.');
    }

    /**
     * Exporta el listado de pacientes en PDF.
     */
    public function exportarPDF()
    {
        $pacientes = Paciente::with('usuario')->get();
        $pdf = \PDF::loadView('reportes.pacientesreportes', compact('pacientes'));
        return $pdf->download('reporte_pacientes.pdf');
    }
}
