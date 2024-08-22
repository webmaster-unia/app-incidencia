<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'id_tco',
    ];

    public $timestamps = false;

    protected $casts = [
        'fecha_incidencia_inc' => 'date',
        'fecha_solucion_inc' =>'date',
        'activo_car' => 'boolean',
        'creado_en'  => 'date_time',
        'actualizado_en'=> 'date_time'
    ];
}