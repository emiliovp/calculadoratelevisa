<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class app_reporteseguimiento extends Model
{
    protected $table = "app_reporteseguimiento";

    protected $fillable = [
        "app"
    ];

    protected $hidden = [];
    protected $primaryKey = '';
    public $timestamps = false;
}
