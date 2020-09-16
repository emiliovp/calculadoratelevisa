<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class FusAreasPerfiles extends Model
{
    protected $table = "fus_areas_perfiles";

    protected $fillable = [
        'id',
        'area',
        'estado',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
