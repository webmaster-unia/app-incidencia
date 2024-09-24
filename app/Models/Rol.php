<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'activo_rol'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_rol' => 'boolean'
    ];    
    
    // Relaciones
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_usu');
    }
    
    public function acciones(): BelongsToMany
    {
        return $this->belongsToMany(Accion::class, 'tbl_rol_permiso', 'id_rol', 'id_acc');
    }
    
    // alcance
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nombre_rol', 'LIKE', "%$search%");
        }
    }
}