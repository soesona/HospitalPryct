<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialesClinicos extends Model
{
    //
    protected $table = 'historiales_clinicos';
    protected $primaryKey = 'codigoHistorial';

    protected $fillable = [
        'codigoPaciente',
        'codigoConsulta',
        'fechaRegistro',
        'descripcion',
    ];

    

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'codigoPaciente', 'codigoPaciente');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'codigoConsulta', 'codigoConsulta');
    }

    public function doctor()
    {

        return $this->belongsTo(Doctor::class, 'codigoDoctor', 'codigoDoctor');
    }

}
