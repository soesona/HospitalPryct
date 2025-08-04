<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultaMedicamentos extends Model
{
    /** @use HasFactory<\Database\Factories\ConsultaMedicamentosFactory> */
    use HasFactory;

    protected $table = 'consulta_medicamentos';

    protected $primaryKey = 'codigoEntrega';

    protected $fillable = [
        'codigoConsulta',
        'codigoMedicamento',
        'cantidadEntregada', 
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'codigoConsulta');
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'codigoMedicamento');
    }

}
