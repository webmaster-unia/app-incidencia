<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;
    protected $table = 'tbl_cargo';
    protected $primaryKey = 'id_car';
    protected $fillable = [
        'id_car',
        'nombre_car',
        'activo_car'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_car' => 'boolean'
    ];
}
