<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObservacionPorRechazo extends Model
{
    protected $table = "fus_observaciones_rechazo";
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'observacion',
        'tipo',
        'rel_configuration_fussyswtl_id',
        'rel_otro_autorizaciones_id',
        'fus_sysadmin_wtl_id'
    ];

    protected $hidden = [];

    public $timestamps = false;

    public function insertMotivoRechazo($idFus, $tipo, $observacion, $idConfigApps = null, $idConfigOtros = null) {
        // $tipo
        // 0 - Jefe
        // 1 - Autorizador
        // 2 - Configuración apps
        // 3 - Configuración otros
        // 4 - Sysadmin
        // 5 - Wintel
        // 6 - Seguridad
        // 7 - Sutis

        $observaciones = new ObservacionPorRechazo;
        $observaciones->observacion = $observacion; 
        $observaciones->tipo = $tipo;
        $observaciones->fus_sysadmin_wtl_id = $idFus;
        $observaciones->rel_configuration_fussyswtl_id = $idConfigApps;
        $observaciones->rel_otro_autorizaciones_id = $idConfigOtros;

        $observaciones->save();

        return $observaciones->id;
    }

    public function getObservacionById($id) {
        return ObservacionPorRechazo::find($id)->toArray();
    }
}
