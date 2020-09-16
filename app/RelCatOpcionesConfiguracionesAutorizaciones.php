<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RelCatOpcionesConfiguracionesAutorizaciones extends Model
{
    protected $table = "rel_catopciones_configuracionesautorizaciones";
    
    protected $fillable = [
        'id', 
        'cat_opciones_id', 
        'fus_configuracion_autorizaciones_id'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function deleteByIdOptCat($idOptCat) {
        RelCatOpcionesConfiguracionesAutorizaciones::where('cat_opciones_id', '=', $idOptCat)->delete();
    }

    public function guardarRelacion($idConfigurado, $idsCatOpc) {
        foreach($idsCatOpc AS $key => $value) {
            $saveRel = new RelCatOpcionesConfiguracionesAutorizaciones;
            $saveRel->cat_opciones_id = $value;
            $saveRel->fus_configuracion_autorizaciones_id = $idConfigurado;

            $saveRel->save();
        }
    }

    public function getDependencias($idcatop) {
        return RelCatOpcionesConfiguracionesAutorizaciones::from('rel_catopciones_configuracionesautorizaciones AS rcc')
        ->select([DB::raw('GROUP_CONCAT(rol_mod_rep) AS rol_mod_rep')])
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rcc.fus_configuracion_autorizaciones_id')
        ->where('rcc.cat_opciones_id', '=', $idcatop)
        ->distinct('rol_mod_rep')
        ->get()
        ->toArray();
    }

    public function guardarRelaciones($idAut, $idOpt) {
        $nuevo = new RelCatOpcionesConfiguracionesAutorizaciones;
        $nuevo->cat_opciones_id = $idOpt;
        $nuevo->fus_configuracion_autorizaciones_id = $idAut;

        if($nuevo->save()) {
            return true;
        }

        return false;
    }
}
