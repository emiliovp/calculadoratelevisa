<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Op_cat_model;

class CatFuses extends Model
{
    protected $table = 'cat_fus';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id',
        'nombre',
        'cve_fus',
        'estatus',
        'created_at',
        'updated_at',
    ];
    
    public function recuperar_opciones()
    {
        return CatFuses::all()->toArray();
        // ->get()->toArray();
    }
}
