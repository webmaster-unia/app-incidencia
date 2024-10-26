<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Incidencia extends Model
{
    use HasFactory;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $table = 'tbl_incidencia';
    protected $primaryKey = 'id_inc';
    protected $fillable = [
        'id_inc',
        'incidencia_inc ',
        'fecha_incidencia_inc',
        'solucion_inc',
        'fecha_solucion_inc',
        'observacion_inc',
        'activo_inc',
        'estado_inc',
        'id_usu',
        'id_tat',
        'id_com',
    ];

    public $timestamps = false;

    protected $casts = [
        'fecha_incidencia_inc' => 'date',
        'fecha_solucion_inc' =>'date',
        'activo_car' => 'boolean',
        'creado_en'  => 'date_time',
        'actualizado_en'=> 'date_time'
    ];

    // Relaciones

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usu');
    }

    public function trabajador_activo(): BelongsTo
    {
        return $this->belongsTo(TrabajadorActivo::class, 'id_tat');
    }

    public function complejidad(): BelongsTo
    {
        return $this->belongsTo(Complejidad::class, 'id_com');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(DB::raw("CONCAT(incidencia_inc, ' ', observacion_inc)"), 'LIKE', "%$search%");
        }
    }
}
