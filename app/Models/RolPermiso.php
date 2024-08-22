<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public $timestamps = false;

}