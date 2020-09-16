<?php

namespace App;

use Illuminate\Support\Facades\DB;
use App\ObservacionPorRechazo;
use App\FUSSysadminWtl;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\NotificacionesController;

class RelFusApps extends Model
{
    protected $table = "rel_fussyswtl_app";

    protected $fillable = [
        'id',
        'applications_id',
        'fus_sysadmin_wtl_id',
        'tipo_movimiento',
        'estado_app',
        'fecha_atencion',
        'fecha_aplicacion',
        'created_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function relFusApp($idfus, $idApp, $cambioEstadoApp = null, $tipoMovimiento) {
        if($cambioEstadoApp != null) {
            $relfusapp = RelFusApps::where('applications_id', '=', $idApp)
            ->where('fus_sysadmin_wtl_id', '=', $idfus);
            $relfusapp->update(['estado_app' => $cambioEstadoApp, 'fecha_aplicacion' =>  DB::raw('now()')]);
        } else {
            $relfusapp = new RelFusApps;
            $relfusapp->applications_id = $idApp;
            $relfusapp->fus_sysadmin_wtl_id = $idfus;
            $relfusapp->tipo_movimiento = $tipoMovimiento;
            if($tipoMovimiento == 3) {
                $relfusapp->estado_app = 2;
            } else {
                $relfusapp->estado_app = 0;
            }
            $relfusapp->save();
        }

    }

    public function getEstaAppFus($idfus, $idapp) {
        return RelFusApps::where('fus_sysadmin_wtl_id', '=', $idfus)
        ->where('applications_id', '=', $idapp)
        ->first();
    }

    public function getAppsFus($idfus) {
        return RelFusApps::where('fus_sysadmin_wtl_id', '=', $idfus)
        ->get()
        ->toArray();
    }
    
    public function automaticoCambioEstadoApp($idapp, $idfus, $estado){
        $fus = new FUSSysadminWtl;
        $app = RelFusApps::where('applications_id', '=', $idapp)
        ->where('fus_sysadmin_wtl_id', '=', $idfus);
        $app->update(['estado_app' => $estado, 'fecha_aplicacion' =>  DB::raw('now()')]);

        $fus->cambioEstadoFusDependiendoApp($idfus);
    }

    public function atendidoApp($idapp, $idfus, $accion, $observacion = null) {
        $app = RelFusApps::where('applications_id', '=', $idapp)
        ->where('fus_sysadmin_wtl_id', '=', $idfus);
        $app->update(['estado_app' => $accion, 'fecha_aplicacion' =>  DB::raw('now()')]);
        if($accion == 1) {
            $rechazo = new ObservacionPorRechazo;
            $idObser = $rechazo->insertMotivoRechazo($idfus, 4, $observacion);

            $noti = new NotificacionesController;
            $noti->sendMailRechazoSolicitante($idfus, $idObser, null, 1);
        }

        $restantes = RelFusApps::where('fus_sysadmin_wtl_id', '=', $idfus)
        ->whereIn('estado_app', [2, 4])
        ->count();

        // if($restantes == 0) {
        //     $fus = new FUSSysadminWtl;
        //     $fus->cambioEstadoFusApp($idfus, 4);
        // }
    }
}
