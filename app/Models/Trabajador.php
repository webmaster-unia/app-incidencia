<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trabajador extends Model
{
    use HasFactory;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $table = 'tbl_trabajador';
    protected $primaryKey = 'id_tra';
    protected $fillable = [
        'id_tra',
        'apellido_paterno_tra',
        'apellido_materno_tra',
        'nombre_tra',
        'activo_tra',
        'id_oca'
    ];

    protected $casts = [
        'activo_tat' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime'
    ];

    //Relaciones

    public function Trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_tra');
    }
    // Relaciones
    public function OficinaCargo(): BelongsTo
    {
        return $this->belongsTo(OficinaCargo::class, 'id_oca');
    }
}


