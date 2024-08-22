<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolPermiso extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_rol_permiso';
    protected $primaryKey = 'id_rpe';
    protected $fillable = [
        'id_rpe',
        'id_acc',
        'id_rol'
    ];
    // Realaciones
    public $timestamps = false;
    public function accion(): BelongsTo
    {
        return $this->belongsTo(Accion::class, 'id_acc');
    }
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

}