<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Doctor extends Model
{
    use HasFactory;
    
    protected $table = 'doctores';
    protected $primaryKey = 'codigoDoctor';
    
    protected $fillable = [
        'codigoUsuario',
        'codigoEspecialidad',
        'is_active'
    ];
    
    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'codigoUsuario', 'codigoUsuario');
    }
    
    // Relación con Especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'codigoEspecialidad', 'codigoEspecialidad');
    }
    
    // Relación con Horarios
    public function horarios()
    {
        return $this->hasMany(Horarios::class, 'codigoDoctor', 'codigoDoctor');
    }
}