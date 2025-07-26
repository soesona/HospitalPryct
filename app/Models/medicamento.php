<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicamento extends Model
{
    /** @use HasFactory<\Database\Factories\MedicamentoFactory> */
    use HasFactory;

    protected $table = 'medicamentos';
    protected $primaryKey = 'codigoMedicamento';
    public $incrementing = true;
    protected $keyType = 'int';
    
}
