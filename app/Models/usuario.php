<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    use HasFactory;
    protected $table = 'tbl_usuario';
    protected $primaryKey = 'id_usu';
    protected $fillable = [
        'id_usu',
        'correo_usu',
        'contraseña_usu',
        'foto_usu',
        'remember_token',
        'activo_usu',
        'id_rol',
        'id_tra',
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_ofi' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];
}


