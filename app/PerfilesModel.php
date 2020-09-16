<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ControlConfigFuseApp;

use App\Op_cat_model;

class PerfilesModel extends Model
{
    public $area = null;
    public $perf = null;
    protected $table = 'fus_perfiles';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id', 
        'perfil', 
        'estado', 
        'modulos_acceso', 
        'created_at',
        'updated_at', 
        'fus_areas_perfiles_id'
    ];

    public function listperfiles(){
        return PerfilesModel::select(['fus_perfiles.id',
        'fus_perfiles.perfil',
        'fus_perfiles.estado',
        'fus_perfiles.modulos_acceso',
        'fus_perfiles.fus_areas_perfiles_id',
        'fus_areas_perfiles.area'])
        ->leftJoin('fus_areas_perfiles', 'fus_areas_perfiles.id', '=', 'fus_perfiles.fus_areas_perfiles_id')
        ->where('fus_perfiles.perfil','<>', "root")
        ->get()
        ->toArray();
    }
    public function perfilesByArea($area,$perfil){
        $this->area = $area;
        $this->perf = $perfil;
        return PerfilesModel::select(['fus_perfiles.id',
        // 'perfil',
        DB::raw("if (fus_perfiles.fus_areas_perfiles_id is null, perfil, concat(perfil,' / ',fus_areas_perfiles.area)) as perfil"),
        'fus_perfiles.estado',   
        'fus_perfiles.modulos_acceso',
        'fus_perfiles.fus_areas_perfiles_id'])
        ->leftJoin('fus_areas_perfiles', 'fus_areas_perfiles.id', '=', 'fus_perfiles.fus_areas_perfiles_id')
        ->where(function($query) {
            if($this->perf != null && $this->perf != 'root'){
                $query->where('fus_perfiles.perfil', '<>', 'root');
                $query->where('fus_perfiles.fus_areas_perfiles_id','=', $this->area);
            }else{
                $query->whereRaw('1 = 1');
            }
        })
        ->get()
        ->toArray();
    }
    public function perfilesById($id){
        $perfil = PerfilesModel::where('id','=', $id)
        ->get()
        ->toArray();
        if ($perfil[0]['perfil'] == 'root') {
            return PerfilesModel::where('id','=', $id)
            ->get()
            ->toArray();
        }else{
            return PerfilesModel::where('perfil','<>', "root")
            ->where('id','=', $id)
            ->get()
            ->toArray();
        }
        // return PerfilesModel::where('perfil','<>', "root")
        // ->where('id','=', $id)
        // ->get()
        // ->toArray();
    }
    public function altaperfil($data){
        $perfila = new PerfilesModel;
        if($perfila->create($data)) {
            return true;
        }

        return false;
    }
    public function bloqueoPerfil($id,$mov){
        $perfil = PerfilesModel::find($id);
        $perfil->estado = $mov;
        $perfil->updated_at = date("Y-m-d H:m:i");
        
        if($perfil->save()) {
            return true;
        }
        
        return false;
    }
    public function editperfil($id,$nombre,$modulos,$area){
        $nombrep = mb_strtoupper($nombre);
        $perfilupd = PerfilesModel::find($id);
        $perfilupd->perfil = $nombrep;
        $perfilupd->modulos_acceso = $modulos;
        $perfilupd->fus_areas_perfiles_id = $area;
        $perfilupd->updated_at = date("Y-m-d H:m:i");

        if($perfilupd->save()) {
            return true;
        }

        return false;
    }
}
