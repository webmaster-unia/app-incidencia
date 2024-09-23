<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


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
    # Relaciones
    public function acciones(): HasMany
    {
        return $this->hasMany(Accion::class, 'id_per ');
    }
}