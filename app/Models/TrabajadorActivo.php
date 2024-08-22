<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrabajadorActivo extends Model
{
    use HasFactory;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $table = 'tbl_tabajador_activo';
    protected $primaryKey = 'id_tat';
    protected $fillable = [
        'id_tat',
        'modelo_tat',
        'detalle_tat',
        'ip_asignado_tat',
        'id_tra',
        'id_ain'
    ];

    protected $casts = [
        'activo_tat' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime'
    ];

    public function TrabajadorActivo(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_tra');
    }
     // Relaciones
    public function ActivoInformatico(): BelongsTo
    {
        return $this->belongsTo(ActivoInformatico::class, 'id_ain');
    }
}


