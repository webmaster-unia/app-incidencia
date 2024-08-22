<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuario extends Authenticatable
{
    use HasFactory;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $table = 'tbl_usuario';
    protected $primaryKey = 'id_usu';
    protected $fillable = [
        'id_usu',
        'correo_usu',
        'contrasena_usu',
        'foto_usu',
        'remember_token',
        'activo_usu',
        'id_rol',
        'id_tra',
    ];

    protected $casts = [
        'activo_tat' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime'
    ];

    //Relaciones

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
    public function trabajador_activo(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_tra');
    }
}


