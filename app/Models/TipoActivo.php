<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoActivo extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_tipo_activo';
    protected $primaryKey = 'id_tac';
    protected $fillable = [
        'id_tac',
        'nombre_tac',
        'activo_tac'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_tac' => 'boolean'
    ];

    // Relaciones
    public function activosInformáticos()
    {
        return $this->hasMany(ActivoInformatico::class);
    }
}