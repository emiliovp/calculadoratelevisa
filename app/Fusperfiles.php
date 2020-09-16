<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Fusperfiles extends Model
{
    protected $table = "fus_perfiles";

    protected $fillable = [
        'id',
        'perfil',
        'estado',
        'modulos_acceso',
        'created_at',
        'updated_at',
        'fus_areas_perfiles_id'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
