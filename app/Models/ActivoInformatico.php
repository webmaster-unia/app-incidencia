<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivoInformatico extends Model
{
    use HasFactory;

    protected $table = 'tbl_activo_informatico';
    protected $primaryKey = 'id_ain';
    protected $fillable = [
        'id_ain',
        'nombre_ain',
        'activo_ain',
        'id_tac',
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_ain' => 'boolean'
    ];
    //relaciones
    
    public function tipo_activo(): BelongsTo
    {
        return $this->belongsTo(TipoActivo::class, 'id_tac');
    }
}