<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Doctor;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $paciente = $usuario->paciente;

        if (!$paciente) {
            $citas = collect(); // colección vacía
        } else {
            $citas = Cita::with(['doctor.user', 'paciente'])
            ->where('codigoPaciente', $paciente->codigoPaciente)
            ->where('estado', 'pendiente') // solo pendientes
            ->orderBy('fechaCita', 'desc') // más recientes primero
            ->orderBy('horaInicio', 'desc') // luego por hora
            ->get();
        }
        $especialidades = Especialidad::all();
        return view('citas.index', compact('citas', 'especialidades'));
    }

    public function create()
    {
        $especialidades = Especialidad::all();
        return view('citas.index', compact('especialidades'));
    }

    // Guardar la cita en la base de datos
    public function store(Request $request)
{
    $request->validate([
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

    // Obtener el usuario logueado y sus relaciones
    $usuario = Auth::user();
    $paciente = $usuario->paciente;
    $doctor = $usuario->doctor;

    if (!$paciente) {
        return back()->withErrors(['error' => 'No se encontró el paciente asociado al usuario.']);
    }

    // Validar que el doctor seleccionado NO sea el mismo usuario (si es doctor)
    if ($doctor && $doctor->codigoDoctor == $request->codigoDoctor) {
        return back()->withErrors(['codigoDoctor' => 'No puedes agendar una cita contigo mismo como doctor.']);
    }

    //  Verificar si el paciente ya tiene una cita PENDIENTE
    $citaPendiente = Cita::where('codigoPaciente', $paciente->codigoPaciente)
        ->where('estado', 'pendiente')
        ->exists();

    if ($citaPendiente) {
        return back()->withErrors(['error' => 'Ya tenés una cita pendiente. Debes esperar a que sea atendida antes de agendar otra.']);
    }

    // Verificar si ya tiene una cita el mismo día
    $citaDelDia = Cita::where('codigoPaciente', $paciente->codigoPaciente)
        ->where('fechaCita', $request->fechaCita)
        ->exists();

    if ($citaDelDia) {
        return back()->withErrors(['fechaCita' => 'Ya tenés una cita agendada ese día.']);
    }

    //  Crear la cita
    Cita::create([
        'codigoPaciente' => $paciente->codigoPaciente,
        'codigoDoctor' => $request->codigoDoctor,
        'fechaCita' => $request->fechaCita,
        'horaInicio' => $horaInicio->format('H:i:s'),
        'horaFin' => $horaFin->format('H:i:s'),
        'estado' => 'pendiente',
    ]);

    return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
}



    // Obtener doctores según la especialidad (AJAX)
    public function getDoctoresPorEspecialidad($id)
{
    $usuario = Auth::user(); // usuario logueado
    $doctorCodigo = $usuario->doctor->codigoDoctor ?? null; // obtener código doctor si existe

    $doctores = Doctor::with('user')
        ->where('codigoEspecialidad', $id)
        ->where('is_active', true)
        // aquí filtramos para excluir al doctor actual si es que existe
        ->when($doctorCodigo, function($query) use ($doctorCodigo) {
            $query->where('codigoDoctor', '!=', $doctorCodigo);
        })
        ->get();

    return response()->json($doctores);
}


    // Obtener horas disponibles según el doctor y la fecha (AJAX)
    public function getHorasDisponibles($codigoDoctor, $fecha)
    {
        $doctor = Doctor::findOrFail($codigoDoctor);

        // Convertir número de día a string
        $diasSemana = [
            0 => 'domingo',
            1 => 'lunes', 
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado'
        ];
        
        $diaSemana = Carbon::parse($fecha)->dayOfWeek;
        $diaSemanaTexto = $diasSemana[$diaSemana];

        // Obtener horarios del doctor para ese día
        $horariosDia = $doctor->horarios()->where('diaSemana', $diaSemanaTexto)->get();

        if ($horariosDia->isEmpty()) {
            return response()->json(['no_horario' => true]);
        }

        $horasDisponibles = [];

        foreach ($horariosDia as $horario) {
            $horaInicio = Carbon::parse($horario->horaInicio);
            $horaFin = Carbon::parse($horario->horaFin);

            $ocupadas = Cita::where('codigoDoctor', $doctor->codigoDoctor)
                ->where('fechaCita', $fecha)
                ->pluck('horaInicio')
                ->toArray();

            while ($horaInicio < $horaFin) {
                $horaStr = $horaInicio->format('H:i:s');

                if ($fecha == Carbon::today()->toDateString() && $horaInicio->lt(Carbon::now())) {
                    $horaInicio->addMinutes(30);
                    continue;
                }

                if (!in_array($horaStr, $ocupadas)) {
                    $horasDisponibles[] = $horaStr;
                }

                $horaInicio->addMinutes(30);
            }
        }

        return response()->json($horasDisponibles);
    }
}
