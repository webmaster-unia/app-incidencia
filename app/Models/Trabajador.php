<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Trabajador extends Model
{
    use HasFactory;

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $table = 'tbl_trabajador';
    protected $primaryKey = 'id_tra';
    protected $fillable = [
        'id_tra',
        'apellido_paterno_tra',
        'apellido_materno_tra',
        'nombres_tra',
        'activo_tra',
        'id_oca'
    ];

    protected $casts = [
        'activo_tat' => 'boolean',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime'
    ];

    //Relaciones

    public function oficina_cargo(): BelongsTo
    {
        return $this->belongsTo(OficinaCargo::class, 'id_oca');
    }

    public function getNombreApellidoAttribute(): string
    {
        $nombre = explode(' ', $this->nombres_tra)[0];
        $apellido = $this->apellido_paterno_tra;
        return $nombre . ' ' . $apellido;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(DB::raw("CONCAT(nombres_tra, ' ', apellido_paterno_tra, ' ', apellido_materno_tra)"), 'LIKE', "%$search%");
        }
    }
}