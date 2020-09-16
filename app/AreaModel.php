<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ControlConfigFuseApp;

class AreaModel extends Model
{
    protected $table = 'fus_areas_perfiles';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id', 
        'area', 
        'estado', 
        'created_at',
        'updated_at'
    ];

    public function listarea(){
        return AreaModel::where('estado', '=', 'Activo')
        ->orderBy('area')
        ->get()
        ->toArray();
    }
    public function listareatodas(){
        return AreaModel::orderBy('area')
        ->get()
        ->toArray();
    }
    public function guardararea($nombre) {
        $area = new AreaModel;
        $area->area = mb_strtoupper($nombre);
        $area->estado = 'Activo';
        $area->updated_at = NULL;
        if($area->save()) {
            return true;
        }
        return false;
    }
    public function editarea($idAEditar, $nombre){
        $area = AreaModel::find($idAEditar);
        $area->area = $nombre;
        $area->updated_at = date("Y-m-d H:m:i");;
        if($area->save()) {
            return true;
        }

        return false;
    }
    public function bloqueoarea($idAEditar, $mov){
        $area = AreaModel::find($idAEditar);
        $area->estado = $mov;
        $area->updated_at = date("Y-m-d H:m:i");;
        if($area->save()) {
            return true;
        }

        return false;
    }
}
