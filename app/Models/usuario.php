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
        'activo_usu' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime'
    ];

    // Relaciones

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'id_tra');
    }

    public function getFotoUsuAttribute($value)
    {
        return $value
            ? asset($value)
            : 'https://ui-avatars.com/api/?name=' . ($this->trabajador->nombre_apellido) . '&color=f4f4f5&background=1d2630&bold=true';
    }
}
