<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

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
        'activo_ofi' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime'
    ];
}


