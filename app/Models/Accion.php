<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Accion extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_accion';
    protected $primaryKey = 'id_acc';
    protected $fillable = [
        'id_acc',
        'nombre_acc',
        'slug_acc',
        'activo_acc',
        'id_per'
    ];

    public $timestamps = false;

    protected $casts = [
        'activo_acc' => 'boolean'
    ];

    // Relaciones

    public function permiso(): BelongsTo
    {
        return $this->belongsTo(Permiso::class, 'id_per');
    }
}
