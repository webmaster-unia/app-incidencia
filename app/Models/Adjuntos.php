<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adjuntos extends Model
{
    use HasFactory;

    protected $table = 'tbl_adjunto';
    protected $primaryKey = 'id_adj';
    protected $fillable = [
        'id_adj',
        'ruta_adj',
        'nombre_adj',
        'activo_adj',
        'id_inc'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_adj' => 'boolean',
    ];
    
    // Relaciones

    public function incidencia(): BelongsTo
    {
        return $this->belongsTo(Incidencia::class, 'id_inc');
    }
}