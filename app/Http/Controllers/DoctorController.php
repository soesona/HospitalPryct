<?php

/**
 * Class DoctorController
 *
 * Controlador para la gestión de doctores en el sistema.
 * Permite listar, obtener, crear, editar y exportar información de doctores.
 *
 * Métodos:
 * - index(): Muestra la lista de doctores activos con sus especialidades y horarios.
 * - obtenerDatos($codigoUsuario): Retorna los datos completos de un doctor específico en formato JSON.
 * - guardarRegistroCrear(Request $request): Valida y crea un nuevo registro de doctor y sus horarios asociados.
 * - guardarRegistroEditar(Request $request): Valida y actualiza la información y horarios de un doctor existente.
 * - exportarPDF(): Genera y descarga un reporte PDF con la información de todos los doctores.
 *
 * Dependencias:
 * - App\Models\User
 * - App\Models\Doctor
 * - App\Models\Especialidad
 * - App\Models\Horarios
 * - Illuminate\Http\Request
 * - PDF (dompdf)
 */
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\Horarios;
use Illuminate\Http\Request;
use PDF;

class DoctorController extends Controller
{
    public function index()
    {
        // Obtener usuarios con rol doctor con todas las relaciones
        $doctores = User::whereHas('roles', function ($query) {
                            $query->where('name', 'Doctor');
                        })
                        ->with(['doctor.especialidad', 'doctor.horarios'])
                        ->select('codigoUsuario', 'nombreCompleto', 'email', 'telefono')
                        ->where('is_active', true)
                        ->get();

        $especialidades = Especialidad::all();

        return view('doctores.index', compact('doctores', 'especialidades'));
    }

    public function obtenerDatos($codigoUsuario)
    {
        $usuario = User::with(['doctor.especialidad', 'doctor.horarios'])
                       ->findOrFail($codigoUsuario);

        return response()->json([
            'usuario' => $usuario,
            'doctor' => $usuario->doctor,
            'horarios' => $usuario->doctor ? $usuario->doctor->horarios : []
        ]);
    }

   public function guardarRegistroCrear(Request $request)
    {
        $request->validate([
            'codigoUsuario' => 'required|exists:users,codigoUsuario',
            'especialidad' => 'required|exists:especialidades,codigoEspecialidad',
            'horarios' => 'required|array|min:1',
            'horarios.*.dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    // Extraer el índice del horario desde el atributo
                    preg_match('/horarios\.(\d+)\.hora_fin/', $attribute, $matches);
                    $index = $matches[1] ?? null;
                    
                    if ($index !== null && isset($request->horarios[$index]['hora_inicio'])) {
                        $horaInicio = $request->horarios[$index]['hora_inicio'];
                        
                        if (strtotime($value) <= strtotime($horaInicio)) {
                            $fail('La hora de fin debe ser mayor que la hora de inicio.');
                        }
                    }
                }
            ],
        ]);

        $usuario = User::findOrFail($request->codigoUsuario);

        if ($usuario->doctor) {
            return response()->json(['message' => 'Este usuario ya tiene un registro de doctor.'], 422);
        }

        $doctor = Doctor::create([
            'codigoUsuario' => $usuario->codigoUsuario,
            'codigoEspecialidad' => $request->especialidad,
        ]);

        foreach ($request->horarios as $horario) {
            Horarios::create([
                'codigoDoctor' => $doctor->codigoDoctor,
                'diaSemana' => strtolower($horario['dia']),
                'horaInicio' => $horario['hora_inicio'],
                'horaFin' => $horario['hora_fin'],
            ]);
        }

        return response()->json(['message' => 'Registro de doctor creado correctamente']);
    }

public function guardarRegistroEditar(Request $request)
    {
        $request->validate([
            'codigoUsuario' => 'required|exists:users,codigoUsuario',
            'especialidad' => 'required|exists:especialidades,codigoEspecialidad',
            'horarios' => 'required|array|min:1',
            'horarios.*.dia' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) use ($request) {
                    // Extraer el índice del horario desde el atributo
                    preg_match('/horarios\.(\d+)\.hora_fin/', $attribute, $matches);
                    $index = $matches[1] ?? null;
                    
                    if ($index !== null && isset($request->horarios[$index]['hora_inicio'])) {
                        $horaInicio = $request->horarios[$index]['hora_inicio'];
                        
                        if (strtotime($value) <= strtotime($horaInicio)) {
                            $fail('La hora de fin debe ser mayor que la hora de inicio.');
                        }
                    }
                }
            ],
        ]);

        $usuario = User::findOrFail($request->codigoUsuario);

        $doctor = $usuario->doctor;
        if (!$doctor) {
            return response()->json(['message' => 'No existe un registro de doctor para este usuario.'], 422);
        }

        $doctor->update([
            'codigoEspecialidad' => $request->especialidad,
        ]);

        $doctor->horarios()->delete();

        foreach ($request->horarios as $horario) {
            Horarios::create([
                'codigoDoctor' => $doctor->codigoDoctor,
                'diaSemana' => strtolower($horario['dia']),
                'horaInicio' => $horario['hora_inicio'],
                'horaFin' => $horario['hora_fin'],
            ]);
        }

        return response()->json(['message' => 'Registro de doctor actualizado correctamente']);
    }

public function exportarPDF()
{
    $doctores= User::whereHas('roles', function ($query) {
        $query->where('name', 'Doctor');
    })->with(['doctor.especialidad', 'doctor.horarios'])->get();
    $pdf = PDF::loadView('reportes.doctoresreportes', compact('doctores'));
    return $pdf->download('reporte_doctores.pdf');
    


}


}
