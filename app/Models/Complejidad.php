<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complejidad extends Model
{
    use HasFactory;
    protected $table = 'tbl_complejidad';
    protected $primaryKey = 'id_com';
    protected $fillable = [
        'id_com',
        'nombre_com',
        'orden_com',
        'activo_com'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_com' => 'boolean'
    ];
}


