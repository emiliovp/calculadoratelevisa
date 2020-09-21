<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CalModulosAcceso extends Model
{
    protected $table = "cal_modulos_acceso";

    protected $fillable = [
        'id',
        'cal_modulos',
        'cal_alias',
        'cal_estado',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function modulosActivos(){
        return CalModulosAcceso::where('cal_estado','=', 'Activo')
        ->orderBy('cal_modulos')
        ->get()
        ->toArray();
    }
    public function modulosconcat($id){
        $a = CalModulosAcceso::select([
            "cal_modulos"
        ])
        ->where('cal_estado','=', 'Activo')
        ->whereIn('id', $id)
        ->orderBy('cal_modulos')
        ->get()
        ->toArray();
        return $a;
    }
    public function modulosByName($mod){

        $a = CalModulosAcceso::select([
            DB::raw('group_concat(cal_alias) as alias'),
           DB::raw('group_concat(id SEPARATOR "_") as idmod') 
        ])
        ->where('cal_estado','=', 'Activo')
        ->whereIn('cal_modulos',$mod)
        ->orderBy('cal_modulos')
        ->get()
        ->toArray();
        return $a;
    }
}
