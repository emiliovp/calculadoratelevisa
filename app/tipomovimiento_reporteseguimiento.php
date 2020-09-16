<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tipomovimiento_reporteseguimiento extends Model
{
    protected $table = "tipomovimiento_reporteseguimiento";

    protected $fillable = [
        "tipo_movimiento"
    ];

    protected $hidden = [];
    protected $primaryKey = '';
    public $timestamps = false;
}
