<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Permiso extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_permiso';
    protected $primaryKey = 'id_per';
    protected $fillable = [
        'id_per',
        'nombre_per',
        'slug_per',
        'activo_per'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_per' => 'boolean'
    ];
}