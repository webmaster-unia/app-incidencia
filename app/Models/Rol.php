<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_rol';
    protected $primaryKey = 'id_rol';
    protected $fillable = [
        'id_rol',
        'nombre_rol',
        'descripción_rol',
        'slug_rol',
        'activo_ofi'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_per' => 'boolean'
    ];
}