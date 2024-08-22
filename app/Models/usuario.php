<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Authenticatable
{
    use HasFactory;

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
        'activo_ofi' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];
}


