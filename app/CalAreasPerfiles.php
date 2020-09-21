<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CalAreasPerfiles extends Model
{
    protected $table = "cal_areas_perfiles";

    protected $fillable = [
        'id',
        'cal_area',
        'cal_estado',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function listarea(){
        return CalAreasPerfiles::where('cal_estado', '=', 'Activo')
        ->orderBy('cal_area')
        ->get()
        ->toArray();
    }
    public function listareatodas(){
        return CalAreasPerfiles::orderBy('cal_area')
        ->get()
        ->toArray();
    }
    public function guardararea($nombre) {
        $area = new CalAreasPerfiles;
        $area->cal_area = mb_strtoupper($nombre);
        $area->cal_estado = 'Activo';
        $area->updated_at = NULL;
        if($area->save()) {
            return true;
        }
        return false;
    }
    public function editarea($idAEditar, $nombre){
        $area = CalAreasPerfiles::find($idAEditar);
        $area->cal_area = $nombre;
        $area->updated_at = date("Y-m-d H:m:i");;
        if($area->save()) {
            return true;
        }

        return false;
    }
    public function bloqueoarea($idAEditar, $mov){
        $area = CalAreasPerfiles::find($idAEditar);
        $area->cal_estado = $mov;
        $area->updated_at = date("Y-m-d H:m:i");;
        if($area->save()) {
            return true;
        }

        return false;
    }
}
