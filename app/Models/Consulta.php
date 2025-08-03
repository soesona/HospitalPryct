<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    //
    protected $table = 'consultas';
    protected $primaryKey = 'codigoConsulta';

    public function paciente()
    {
    return $this->belongsTo(\App\Models\Paciente::class, 'codigoPaciente');
    }

    public function doctor()
    {
    return $this->belongsTo(\App\Models\Doctor::class, 'codigoDoctor');
    }

    public function enfermedad()
    {
    return $this->belongsTo(\App\Models\Enfermedad::class, 'codigoEnfermedad');
    }

}
