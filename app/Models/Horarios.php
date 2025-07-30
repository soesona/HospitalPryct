<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Horarios extends Model
{
    use HasFactory;
    
    protected $table = 'horarios';
    protected $primaryKey = 'codigoHorario';
    
    protected $fillable = [
        'codigoDoctor',
        'diaSemana',
        'horaInicio',
        'horaFin'
    ];
    
    protected $casts = [
        'horaInicio' => 'datetime:H:i',
        'horaFin' => 'datetime:H:i',
    ];
    
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'codigoDoctor', 'codigoDoctor');
    }
}
