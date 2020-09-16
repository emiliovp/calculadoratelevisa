<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class FusModulosAcceso extends Model
{
    protected $table = "fus_modulos_acceso";

    protected $fillable = [
        'id',
        'modulo',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
