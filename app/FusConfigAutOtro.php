<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CatFuses;
use App\RelOtrosAutorizaciones;

class FusConfigAutOtro extends Model
{
    protected $table="conf_aut_otros";

    protected $fillable = [
        'id',
        'numero_empleado',
        'correo', 
        'nombre', 
        'usuario_red', 
        'estatus',
        'tcs_cat_helpdesk_id', 
        'cat_fus_id', 
        'created_at', 
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getConfiguracionAutorizaciones($clave, $tipo) {
        return FusConfiguracionesAutorizaciones::where('claveapp_temp', '=', $clave)
        ->where('tipo_autorizacion', '=', $tipo)
        ->get()
        ->toArray();
    }
    
    public function getCorreosAutorizadores($tipo) {
        return FusConfigAutOtro::select('conf_aut_otros.id')
        ->join('cat_fus', 'cat_fus.id', '=', 'conf_aut_otros.cat_fus_id')
        ->where('cat_fus.cve_fus', '=', $tipo)
        ->get()
        ->toArray();
    }

    public function getConfiguracionAutorizacionesByIdFUS($id) {
        return RelOtrosAutorizaciones::select(['conf_aut_otros.correo', 'rel_otro_autorizaciones.id AS idRelConf'])
        ->join('conf_aut_otros', 'conf_aut_otros.id', '=', 'rel_otro_autorizaciones.conf_aut_otros_id')
        ->where('rel_otro_autorizaciones.fus_sysadmin_wtl_id', '=', $id)
        ->where('rel_otro_autorizaciones.estado', '=', '0')
        ->where('conf_aut_otros.estatus', '=', '1')
        ->get()
        ->toArray();
    }

    public function getConfiguraciones() {
        return FusConfiguracionesAutorizaciones::select('claveapp_temp')->groupBy('claveapp_temp')->get()->toArray();
    }

    public function getConfiguracionAutorizacionesByID($id) {
        $config = FusConfiguracionesAutorizaciones::select('rol_mod_rep')
        ->find($id);

        return $config['rol_mod_rep'];
    }
    public function lista()
    {
        return  FusConfigAutOtro::select('conf_aut_otros.id as id_config','conf_aut_otros.numero_empleado', 
        'conf_aut_otros.nombre as nombre_emp', 
        'conf_aut_otros.correo', 'cat_fus.nombre as fus')
        ->join('cat_fus','cat_fus.id','=','conf_aut_otros.cat_fus_id')
        ->where('conf_aut_otros.estatus', '<>', 2 )
        ->get()
        ->toArray();
    }
    public function baja_logica($val)
    {
        $data = FusConfigAutOtro::find($val);
        $data->estatus = 2;
        $data->save();
    }
}
