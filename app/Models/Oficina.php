<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // Relaciones

    public function cargos() :BelongsToMany
    {
        return $this->belongsToMany(Cargo::class, 'tbl_oficina_cargo', 'id_ofi', 'id_car');
    }

    // Alcance

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nombre_ofi', 'LIKE', "%$search%");
        }
    }
}
