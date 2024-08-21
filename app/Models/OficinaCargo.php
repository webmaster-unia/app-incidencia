<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}