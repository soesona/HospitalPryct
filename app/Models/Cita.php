<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $primaryKey = 'codigoCita';

    protected $fillable = [
        'codigoPaciente',
        'codigoDoctor',
        'fechaCita',
        'horaInicio',
        'horaFin',
        'estado',
    ];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'codigoPaciente');
    }

    // Relación con Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'codigoDoctor');
    }
}
