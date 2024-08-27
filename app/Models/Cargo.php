<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // Relaciones

    public function oficinas() :BelongsToMany
    {
        return $this->belongsToMany(Oficina::class, 'tbl_oficina_cargo', 'id_car', 'id_ofi');
    }
}
