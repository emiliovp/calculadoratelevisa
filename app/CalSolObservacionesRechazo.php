<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalSolObservacionesRechazo extends Model
{
    protected $table = "cal_sol_observaciones_rechazo";
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'cal_observacion',
        'cal_tipo',
        'cal_solicitud_id'
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

        $observaciones = new CalSolObservacionesRechazo;
        $observaciones->cal_observacion = $observacion; 
        $observaciones->cal_tipo = $tipo;
        $observaciones->cal_solicitud_id = $idFus;

        $observaciones->save();

        return $observaciones->id;
    }

    public function getObservacionById($id) {
        return CalSolObservacionesRechazo::find($id)->toArray();
    }
}
