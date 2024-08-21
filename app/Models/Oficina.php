<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    use HasFactory;

    protected $table = 'tbl_oficina';
    protected $primaryKey = 'id_ofi';
    protected $fillable = [
        'id_ofi',
        'nombre_ofi',
        'activo_ofi'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_ofi' => 'boolean'
    ];
}
