<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\FUSSysadminWtl;
use App\RelOtrosAutorizaciones;
use App\LogBookMovements;
use App\RelConfigurationfussyswtl;
use App\RelFusApps;
use App\Applications;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\FusAutorizacionesController;

class FusController extends Controller
{
    public $ip_address_client;
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function showFus(Request $request) {
        $bitacora = new LogBookMovements;
        $apps = new Applications;

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización del FUS #'.$request->id,
            'tipo' => 'vista',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );
        
        $bitacora->guardarBitacora($data);

        $tipoShow = 0; // 0 - Visualización\n1 - Visualización por autorización
        $jefeOAut = 0; // 1 - Jefe\n2 - Autorizador
        
        $infoRelAuto = '';
        
        if($request->tipo) {
            $tipoShow = $request->tipo;
            $jefeOAut = $request->jefeOAut;
        }

        $yaAutorizado = null;

        if(isset($request->idconfig)) {
            switch ($request->tiporelconfig) {
                case 0:
                    // En caso de apps config
                    $getRelAuto = new RelConfigurationfussyswtl;
                    $jefeOAut = $request->jefeOAut;
                    $yaAutorizado = $getRelAuto->getValidacionAutorizacion($request->idconfig);
                break;
                
                case 1:
                    // En caso de otros config
                    $getRelAuto = new RelOtrosAutorizaciones;
                    $jefeOAut = 4;
                    break;
            }

            $infoRelAuto = $getRelAuto->getRelConfigAutoById($request->idconfig);
        }

        $fus = new FUSSysadminWtl;
        $fusProcesado = $fus->getFusInfProcesada($request->id);
        $idapp = null;
        
        if($request->idapp && !empty($request->idapp)) {
            $idapp = $request->idapp;
        }

        // Calculo
        $checksNotis = array();
        if($fusProcesado['tipo_fus'] == 0) {
            $checks = new FusAutorizacionesController;
            $checks = $checks->calculoParaEnvioDeMail($fusProcesado['id']);
            
            foreach ($checks as $tipo => $value) {
                foreach($value AS $idapp => $content) {
                    if($tipo != "calculocompleto") {
                        foreach($content AS $objeto => $check) {
                            if($check == 1 || $check != 0) {
                                if(!isset($checksNotis[$tipo][$idapp])) {
                                    $checksNotis[$tipo][$idapp] = 1;
                                } elseif($checksNotis[$tipo][$idapp] > 0) {
                                    $checksNotis[$tipo][$idapp] = $checksNotis[$tipo][$idapp]+1;
                                }
                            } else if($check == 0) {
                                if(!isset($checksNotis[$tipo][$idapp])) {
                                    $checksNotis[$tipo][$idapp] = 0;
                                } else {
                                    $checksNotis[$tipo][$idapp] = 0;
                                }
                            }
                        }
                    }
                }
            }
            foreach($checks['calculocompleto'] AS $row) {
                $nombreApp = $apps->getNameApplicationById($row['applications_id']);
                $checksNotis['calculocompleto'][$nombreApp][] = $row;
            }
        } else {
            $checksNotis = "";
        } 
        // dd($checksNotis);
        // Fin
        
        return view('fus.show')->with(['fus' => $fusProcesado, 'tipo' => $tipoShow, 'jefeOAut' => $jefeOAut, 'idRelConf' => $infoRelAuto, 'idapp' => $idapp, 'yaAutorizado' => $yaAutorizado, 'trackApps' => $checksNotis]);
    }
    public function getStatusApp($id, $idFus) {
        $app = new RelFusApps;
        return $app->getEstaAppFus($idFus, $id);
    }
    public function rechazofusjefe(Request $request) {
        $fus = new FUSSysadminWtl;
        $idObse = $fus->disicionfusjefe($request->post('id'), $request->post('jefeOAut'), $request->post('tipoAccion'), $request->post('observaciones'), $request->post('idRelConf'));
        
        if($idObse != "") {
            $sendMailRechazo = new NotificacionesController;
            $sendMailRechazo->sendMailRechazoSolicitante($request->post('id'), $idObse, $request->post('jefeOAut'));
        }
    }

    public function autorizacionjefe(Request $request) {
        $fus = new FUSSysadminWtl;
        $fus->disicionfusjefe($request->post('id'), $request->post('jefeOAut'), $request->post('tipoAccion'), null, $request->post('idRelConf'));
        
        if($request->post('jefeOAut') != 4) {
            $notificaciones = new NotificacionesController;
            $notificaciones->sendMailAutorizacionApps($request->post('id'), /*$idapp, */$request->post('idRelConf'));
        }

    }

    public function aplicacionapp(Request $request) {
        $app = new RelFusApps;
        $fus = new FUSSysadminWtl;
        $app->atendidoApp($request->post('idapp'), $request->post('idfus'), $request->post('accion'), $request->post('observaciones'));
        $appsEstado = $app->getAppsFus($request->post('idfus'));
        
        $paraRechazo = 0;
        $paraCambiarAtendido = 0;
        $total = count($appsEstado);
        
        foreach($appsEstado AS $index => $value) {
            if($value['estado_app'] == 2) {
                $paraRechazo = $paraRechazo+1;
            }
            if(
                $value['estado_app'] == 1 ||
                $value['estado_app'] == 3
            ) {
                $paraCambiarAtendido = $paraCambiarAtendido+1;
            }
        }
            
        if($total == $paraRechazo) {
            $fus->cambioEstadoFusApp($request->post('idfus'), 3);
        } else if ($total == $paraCambiarAtendido) {
            $fus->cambioEstadoFusApp($request->post('idfus'), 4);
        }
    }
}
