<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enfermedad extends Model
{
    protected $table = 'enfermedades'; 
    protected $primaryKey = 'codigoEnfermedad';
}
