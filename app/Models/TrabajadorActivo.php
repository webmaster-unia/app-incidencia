<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabajadorActivo extends Model
{
    use HasFactory;

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
}


