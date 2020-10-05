<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Calperfiles extends Model
{
    public $area = null;
    public $perf = null;

    protected $table = "cal_perfiles";

    protected $fillable = [
        'id',
        'cal_perfil',
        'cal_estado',
        'cal_modulos_acceso',
        'created_at',
        'updated_at',
        'cal_areas_perfiles_id'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function listperfiles(){
        return Calperfiles::select(['cal_perfiles.id',
        'cal_perfiles.cal_perfil',
        'cal_perfiles.cal_estado',
        'cal_perfiles.cal_modulos_acceso',
        'cal_perfiles.cal_areas_perfiles_id',
        'cal_areas_perfiles.cal_area'])
        ->leftJoin('cal_areas_perfiles', 'cal_areas_perfiles.id', '=', 'cal_perfiles.cal_areas_perfiles_id')
        ->where('cal_perfiles.cal_perfil','<>', "root")
        ->get()
        ->toArray();
    }
    public function perfilesByArea($area,$perfil){
        $this->area = $area;
        $this->perf = $perfil;
        return Calperfiles::select(['cal_perfiles.id',
        // 'perfil',
        DB::raw("if (cal_perfiles.cal_areas_perfiles_id is null, cal_perfil, concat(cal_perfil,' / ',cal_areas_perfiles.cal_area)) as perfil"),
        'cal_perfiles.cal_estado',   
        'cal_perfiles.cal_modulos_acceso',
        'cal_perfiles.cal_areas_perfiles_id'])
        ->leftJoin('cal_areas_perfiles', 'cal_areas_perfiles.id', '=', 'cal_perfiles.cal_areas_perfiles_id')
        ->where(function($query) {
            if($this->perf != null && $this->perf != 'root'){
                $query->where('cal_perfiles.cal_perfil', '<>', 'root');
                $query->where('cal_perfiles.cal_areas_perfiles_id','=', $this->area);
            }else{
                $query->whereRaw('1 = 1');
            }
        })
        ->get()
        ->toArray();
    }
    public function perfilesById($id){
        $perfil = Calperfiles::where('id','=', $id)
        ->get()
        ->toArray();
        if ($perfil[0]['cal_perfil'] == 'root') {
            return Calperfiles::where('id','=', $id)
            ->get()
            ->toArray();
        }else{
            return Calperfiles::where('cal_perfil','<>', "root")
            ->where('id','=', $id)
            ->get()
            ->toArray();
        }
        // return Calperfiles::where('perfil','<>', "root")
        // ->where('id','=', $id)
        // ->get()
        // ->toArray();
    }
    public function altaperfil($data){
        $perfila = new Calperfiles;
        if($perfila->create($data)) {
            return true;
        }

        return false;
    }
    public function bloqueoPerfil($id,$mov){
        $perfil = Calperfiles::find($id);
        $perfil->cal_estado = $mov;
        $perfil->updated_at = date("Y-m-d H:m:i");
        
        if($perfil->save()) {
            return true;
        }
        
        return false;
    }
    public function editperfil($id,$nombre,$modulos,$area){
        $nombrep = mb_strtoupper($nombre);
        $perfilupd = Calperfiles::find($id);
        $perfilupd->cal_perfil = $nombrep;
        $perfilupd->cal_modulos_acceso = $modulos;
        $perfilupd->cal_areas_perfiles_id = $area;
        $perfilupd->updated_at = date("Y-m-d H:m:i");

        if($perfilupd->save()) {
            return true;
        }

        return false;
    }
}
