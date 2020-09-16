<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tipofus_reporteseguimiento extends Model
{
    protected $table = "tipofus_reporteseguimiento";

    protected $fillable = [
        "tipo_fus"
    ];

    protected $hidden = [];
    protected $primaryKey = '';
    public $timestamps = false;

}
