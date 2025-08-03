<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminCitaController extends Controller
{
    /**
     * Mostrar la vista principal de gestión de citas para administradores
     */
    public function index()
{
    // Obtener solo citas pendientes, confirmadas y canceladas (no finalizadas)
    $citas = Cita::with(['doctor.user', 'doctor.especialidad', 'paciente.usuario'])
        ->whereIn('estado', ['pendiente', 'confirmada', 'cancelada'])
        ->orderByRaw("CASE 
            WHEN estado = 'pendiente' THEN 1 
            WHEN estado = 'confirmada' THEN 2 
            WHEN estado = 'cancelada' THEN 3 
            ELSE 4 END")
        ->orderBy('fechaCita', 'asc')
        ->orderBy('horaInicio', 'asc')
        ->get();

    $especialidades = Especialidad::all();
    
    return view('citas.admin', compact('citas', 'especialidades'));
}

    /**
     * Buscar pacientes por nombre (AJAX)
     */
    public function buscarPacientes(Request $request)
{
    $query = $request->get('q');
    
    // Cambié la validación para números de identidad
    if (strlen($query) < 4) {
        return response()->json([]);
    }

    $pacientes = Paciente::with('usuario')
        ->whereHas('usuario', function($q) use ($query) {
            $q->where('identidad', 'LIKE', '%' . $query . '%');
        })
        ->limit(10)
        ->get();

    return response()->json($pacientes);
}

    /**
     * Obtener doctores por especialidad excluyendo al paciente si es doctor (AJAX)
     */
    public function getDoctoresPorEspecialidad($especialidadId, $pacienteId)
    {
        try {
            // Obtener el paciente seleccionado
            $paciente = Paciente::with('usuario')->findOrFail($pacienteId);
            
            // Verificar si el paciente también es doctor
            $doctorPaciente = Doctor::where('codigoUsuario', $paciente->codigoUsuario)->first();
            
            $query = Doctor::with('user') // Asegurar que carga la relación user
                ->where('codigoEspecialidad', $especialidadId)
                ->where('is_active', true);
                
            // Si el paciente también es doctor, excluirlo de la lista
            if ($doctorPaciente) {
                $query->where('codigoDoctor', '!=', $doctorPaciente->codigoDoctor);
            }
            
            $doctores = $query->get();
            
            // Debug: verificar que se carguen correctamente
            foreach ($doctores as $doctor) {
                if (!$doctor->user) {
                    \Log::warning("Doctor sin relación user: " . $doctor->codigoDoctor);
                }
            }

            return response()->json($doctores);
            
        } catch (\Exception $e) {
            \Log::error("Error en getDoctoresPorEspecialidad: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar doctores'], 500);
        }
    }

    /**
     * Almacenar una nueva cita creada por el administrador
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigoPaciente' => 'required|exists:pacientes,codigoPaciente',
            'codigoDoctor' => 'required|exists:doctores,codigoDoctor',
            'fechaCita' => 'required|date|after_or_equal:today',
            'horaInicio' => 'required|date_format:H:i',
        ]);

        $horaInicio = Carbon::createFromFormat('H:i', $request->horaInicio);
        $horaFin = $horaInicio->copy()->addMinutes(30);

        // Verificar que la hora no esté ocupada
        $existe = Cita::where('codigoDoctor', $request->codigoDoctor)
            ->where('fechaCita', $request->fechaCita)
            ->where('horaInicio', $horaInicio->format('H:i:s'))
            ->exists();

        if ($existe) {
            return back()->withErrors(['horaInicio' => 'Esa hora ya está ocupada.']);
        }

        // Verificar si el paciente ya tiene una cita PENDIENTE
        $citaPendiente = Cita::where('codigoPaciente', $request->codigoPaciente)
            ->where('estado', 'pendiente')
            ->exists();

        if ($citaPendiente) {
            return back()->withErrors(['error' => 'Este paciente ya tiene una cita pendiente. Debe esperar a que sea atendida antes de agendar otra.']);
        }

        // Verificar si ya tiene una cita el mismo día
        $citaDelDia = Cita::where('codigoPaciente', $request->codigoPaciente)
            ->where('fechaCita', $request->fechaCita)
            ->exists();

        if ($citaDelDia) {
            return back()->withErrors(['fechaCita' => 'Este paciente ya tiene una cita agendada para ese día.']);
        }

        // Obtener información del paciente y doctor para el mensaje
        $paciente = Paciente::with('usuario')->findOrFail($request->codigoPaciente);
        $doctor = Doctor::with('user')->findOrFail($request->codigoDoctor);

        // Crear la cita
        Cita::create([
            'codigoPaciente' => $request->codigoPaciente,
            'codigoDoctor' => $request->codigoDoctor,
            'fechaCita' => $request->fechaCita,
            'horaInicio' => $horaInicio->format('H:i:s'),
            'horaFin' => $horaFin->format('H:i:s'),
            'estado' => 'pendiente',
        ]);

        $nombrePaciente = ucwords(strtolower($paciente->usuario->nombreCompleto));
        $nombreDoctor = ucwords(strtolower($doctor->user->nombreCompleto));
        $fechaFormateada = Carbon::parse($request->fechaCita)->format('d/m/Y');
        
        return redirect()->route('admin.citas.index')
            ->with('success', "Cita agendada exitosamente para {$nombrePaciente} con Dr. {$nombreDoctor} el {$fechaFormateada} a las {$request->horaInicio}");
    }

    /**
     * Cambiar el estado de una cita
     */
    public function cambiarEstado(Request $request, $codigoCita)
{
    $request->validate([
        'estado' => 'required|in:pendiente,confirmada,cancelada'
    ]);

    $cita = Cita::with(['paciente.usuario', 'doctor.user'])->findOrFail($codigoCita);

    if ($cita->estado === 'cancelada') {
        $mensajeError = 'No se puede cambiar el estado de una cita cancelada.';
        if ($request->ajax()) {
            return response()->json(['error' => $mensajeError], 400);
        }
        return redirect()->route('admin.citas.index')->with('error', $mensajeError);
    }

    $estadoAnterior = $cita->estado;
    $cita->update(['estado' => $request->estado]);

    $nombrePaciente = ucwords(strtolower($cita->paciente->usuario->nombreCompleto));
    $estadoTexto = ucfirst($request->estado);

    $mensaje = "Estado de la cita de {$nombrePaciente} cambiado de '{$estadoAnterior}' a '{$estadoTexto}' exitosamente.";

    if ($request->ajax()) {
        return response()->json(['success' => true, 'mensaje' => $mensaje]);
    }
    return redirect()->route('admin.citas.index')->with('success', $mensaje);
}



    /**
     * Obtener todas las citas (para una vista más completa si se necesita)
     */
    public function todasLasCitas()
    {
        $citas = Cita::with(['doctor.user', 'doctor.especialidad', 'paciente.usuario'])
            ->orderBy('fechaCita', 'desc')
            ->orderBy('horaInicio', 'desc')
            ->paginate(20);

        return view('admin.citas.todas', compact('citas'));
    }

    /**
     * Obtener estadísticas de citas
     */
    public function estadisticas()
    {
        $hoy = Carbon::today();
        $esteMes = Carbon::now()->startOfMonth();
        
        $estadisticas = [
            'citas_hoy' => Cita::whereDate('fechaCita', $hoy)->count(),
            'citas_pendientes' => Cita::where('estado', 'pendiente')->count(),
            'citas_este_mes' => Cita::whereBetween('fechaCita', [$esteMes, Carbon::now()])->count(),
            'citas_confirmadas_hoy' => Cita::whereDate('fechaCita', $hoy)
                ->where('estado', 'confirmada')
                ->count(),
        ];

        return response()->json($estadisticas);
    }

    /**
     * Cancelar múltiples citas (funcionalidad adicional)
     */
    public function cancelarMultiples(Request $request)
    {
        $request->validate([
            'citas' => 'required|array',
            'citas.*' => 'exists:citas,codigoCita'
        ]);

        $citasCanceladas = Cita::whereIn('codigoCita', $request->citas)
            ->where('estado', '!=', 'cancelada')
            ->update(['estado' => 'cancelada']);

        return redirect()->route('admin.citas.index')
            ->with('success', "Se cancelaron {$citasCanceladas} cita(s) exitosamente.");
    }

    /**
     * Exportar citas a CSV (funcionalidad adicional)
     */
    public function exportarCSV(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', Carbon::now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', Carbon::now()->endOfMonth());

        $citas = Cita::with(['doctor.user', 'doctor.especialidad', 'paciente.usuario'])
            ->whereBetween('fechaCita', [$fechaInicio, $fechaFin])
            ->orderBy('fechaCita', 'asc')
            ->get();

        $filename = "citas_" . Carbon::now()->format('Y_m_d_H_i_s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($citas) {
            $file = fopen('php://output', 'w');
            
            // Encabezados del CSV
            fputcsv($file, [
                'Código Cita',
                'Paciente',
                'Doctor', 
                'Especialidad',
                'Fecha',
                'Hora Inicio',
                'Hora Fin',
                'Estado'
            ]);

            // Datos de las citas
            foreach ($citas as $cita) {
                fputcsv($file, [
                    $cita->codigoCita,
                    $cita->paciente->usuario->nombreCompleto ?? 'N/A',
                    $cita->doctor->user->nombreCompleto ?? 'N/A',
                    $cita->doctor->especialidad->nombre ?? 'N/A',
                    Carbon::parse($cita->fechaCita)->format('d/m/Y'),
                    Carbon::parse($cita->horaInicio)->format('H:i'),
                    Carbon::parse($cita->horaFin)->format('H:i'),
                    ucfirst($cita->estado)
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}