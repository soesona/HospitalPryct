<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidades';
    protected $primaryKey = 'codigoEspecialidad';
    protected $fillable = ['nombre'];
    public $timestamps = true;
}
