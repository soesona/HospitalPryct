<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'codigoPaciente'; // si usas PK no estándar

    protected $fillable = ['codigoUsuario', /* otros campos */];

    public function user()
    {
        return $this->belongsTo(User::class, 'codigoUsuario', 'id');
    }
}
