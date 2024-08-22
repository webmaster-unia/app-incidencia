<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OficinaCargo extends Model
{
    use HasFactory;

    protected $table = 'tbl_oficina_cargo';
    protected $primaryKey = 'id_oca';
    protected $fillable = [
        'id_oca',
        'id_ofi',
        'id_car'
    ];

    public $timestamps = false;
    
    // Relaciones

    public function oficina(): BelongsTo
    {
        return $this->belongsTo(Oficina::class, 'id_ofi');
    }
    
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'id_car');
    }
}