<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\LogBookMovements;
use App\FUSSysadminWtl;
use App\RelAnexosFus;
use App\RelFusApps;
use App\Applications;
use App\RelConfigurationfussyswtl;
use App\FusConfiguracionesAutorizaciones;
use App\Http\Controllers\FusSysadminController;
use Yajra\Datatables\Datatables;

class FusAutorizacionesController extends Controller
{
    public $ip_address_client;
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function getAnexos($idFus, $idsApps) {
        $anexos = new RelAnexosFus;
        $FusSysadminController = new FusSysadminController;
        $infoAnexos = array();
        foreach($idsApps AS $k => $v) {
            $tituloApp = $FusSysadminController->appsTitulos($v);
            $archivo = $anexos->getAllsSRCAnexo($v, $idFus);
            if(count($archivo) > 0) {
                $infoAnexos[$tituloApp] = $anexos->getAllsSRCAnexo($v, $idFus);
            }
        }

        return $infoAnexos;
    }

    public function index(Request $request) {
        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Empleado con #'.$noEmployee.' visualizó lista de FUS-e por autorizar',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        $msjOk = 0;
        
        if(isset($request->msjOk)) {
            $msjOk = $request->msjOk;
        }
        return view('fus.lista_fuses_por_autorizar')->with('msjOk', $msjOk);
    }

    public function dataIndex() {
        $noEmp = Auth::user()->noEmployee;
        $fuses = new FUSSysadminWtl;    
        $data = $fuses->listaFusPorAutorizar($noEmp);
        return Datatables::of($data)->make(true);
    }

    public function formatoAlNombreDeLaAutorizacion($cuerpo, $idConf, $objFusConfiguracionesAutorizaciones, $tipo = 0) {
        // dd($cuerpo, $objFusConfiguracionesAutorizaciones);
        foreach($cuerpo AS $index => $value) {
            if($value['tipo_solicitud']['valor'] != 'b') {
                $tipoPI = '';
                switch($index) {
                    case 16:
                    case 64:
                        $ResponsabilidadesProcesadas = '';
                        $formateadaprod = explode(',', $value['reporte_prod']['valor']);
                        $formateadaintermex = explode(',', $value['reporte_intermex']['valor']);
    
                        foreach($formateadaprod AS $index => $val) {
                            if(!empty($val)) {
                                $ResponsabilidadesProcesadas .= $objFusConfiguracionesAutorizaciones->getConfiguracionAutorizacionesByIdERPDiscovererFlijoAut(null, $val, $idConf);
                            }
                        }
                        
                        if($ResponsabilidadesProcesadas != "" && $tipo == 0) {
                            return "Prod";
                        }
                        
                        foreach($formateadaintermex AS $index => $val) {
                            if(!empty($val)) {
                                $ResponsabilidadesProcesadas .= $objFusConfiguracionesAutorizaciones->getConfiguracionAutorizacionesByIdERPDiscovererFlijoAut(null, $val, $idConf);
                            }
                        }
    
                        if($ResponsabilidadesProcesadas != "" && $tipo == 0) {
                            return "Intermex";
                        }
                        break;
                    case 20:
                        switch ($tipo) {
                            case 0:
                                $ResponsabilidadesProcesadas = '';
                                $formateadaprod = explode(',', $value['responsabilidad_prod']['valor']);
                                $formateadaintermex = explode(',', $value['responsabilidad_intermex']['valor']);
    
                                foreach($formateadaprod AS $index => $val) {
                                    if(!empty($val)) {
                                        $temp = explode('-', $val);
                                        $ResponsabilidadesProcesadas .= $objFusConfiguracionesAutorizaciones->getConfiguracionAutorizacionesByIdERPDiscovererFlijoAut($temp[0], $temp[1], $idConf);
                                    }
                                }
                                
                                if($ResponsabilidadesProcesadas != "" && $tipo == 0) {
                                    return "Prod";
                                }
                                
                                foreach($formateadaintermex AS $index => $val) {
                                    if(!empty($val)) {
                                        $temp = explode('-', $val);
                                        $ResponsabilidadesProcesadas .= $objFusConfiguracionesAutorizaciones->getConfiguracionAutorizacionesByIdERPDiscovererFlijoAut($temp[0], $temp[1], $idConf);
                                    }
                                }
    
                                if($ResponsabilidadesProcesadas != "" && $tipo == 0) {
                                    return "Intermex";
                                }
                                break;
                            case 1:
                                # code...
                                $ResponsabilidadesProcesadas = '';
                                $formateadaprod = explode(',', $value['responsabilidad_prod']['valor']);
                                foreach($formateadaprod AS $index => $val) {
                                    if(!empty($val)) {
                                        $temp = explode('-', $val);
                                        $ResponsabilidadesProcesadas .= $objFusConfiguracionesAutorizaciones->getConfiguracionAutorizacionesByIdERPDiscovererFlijoAut($temp[0], $temp[1], $idConf);
                                    }
                                }
                                break;
                            
                            case 2:
                                # code...
                                $ResponsabilidadesProcesadas = '';
                                $formateadaintermex = explode(',', $value['responsabilidad_intermex']['valor']);
                                foreach($formateadaintermex AS $index => $val) {
                                    if(!empty($val)) {
                                        $temp = explode('-', $val);
                                        $ResponsabilidadesProcesadas .= $objFusConfiguracionesAutorizaciones->getConfiguracionAutorizacionesByIdERPDiscovererFlijoAut($temp[0], $temp[1], $idConf);
                                    }
                                }
                                break;
                        }
                        break;
                }
            }
        }

        return substr($ResponsabilidadesProcesadas, 0, -2);
    }

    public function fusarevisar(Request $request) {
        $bitacora = new LogBookMovements;
        $getEncabezadoFus = new FUSSysadminWtl;
        $fuses = new RelConfigurationfussyswtl;
        $FusSysadminController = new FusSysadminController;
        $validacionAutRech = new FusConfiguracionesAutorizaciones;

        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);

        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Empleado con #'.$noEmployee.' visualizó lista de FUS-e por autorizar',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        
        $bitacora->guardarBitacora($data);

        $datos = array();

        $datos['idFus'] = $request->id;
        $datos['noEmp'] = $noEmployee;
        
        
        $data = $fuses->autorizacionesarevisar($datos);
        
        if(
            count($data) == 0 || 
            $data[0]['autorizo_jefe'] == 0 ||
            $data[0]['no_empleado_aut'] !=  null && 
            $data[0]['aut_autorizo'] == 0
        ) {
            return redirect()->route('listafusesporautorizar');
        }

        $fus_cuerpo = '';
        
        if(count($data) > 0){
            $fus_cuerpo = json_decode($data[0]['fus_cuerpo'], true);
        }
        
        $apps = $fuses->getAppsOfFusPantallaMesasAutorizaciones($datos['idFus']);
        $idsApps = array();
        foreach($apps AS $kapps => $vapps) {
            $idsApps[] = $vapps['applications_id'];
        }
        $mostrarAnexos = $this->getAnexos($datos['idFus'], $idsApps);
        $datos['dataApps'] = $data;
        
        $countPosition = 0;
        $dataReOrdenado = array();
        
        $newData = array();

        foreach($data AS $row => $content) {
            $sendData = array();
            $sendData['idFus'] = $datos['idFus'];
            $sendData['idApp'] = $content['applications_id'];
            
            $sendData['nombreObjeto'] = $content['rol_mod_rep'];
            if(isset($content['fus_configuracion_autorizaciones_id'])) {
                switch ($content['applications_id']) {
                    case 20:
                        $data[$countPosition]['rol_mod_rep_formateado'] = $this->formatoAlNombreDeLaAutorizacion($fus_cuerpo, $content['fus_configuracion_autorizaciones_id'], $validacionAutRech, 1);
                        
                        if(empty($data[$countPosition]['rol_mod_rep_formateado'])) {
                            $data[$countPosition]['rol_mod_rep_formateado'] = $this->formatoAlNombreDeLaAutorizacion($fus_cuerpo, $content['fus_configuracion_autorizaciones_id'], $validacionAutRech, 2);
                        }
                        $data[$countPosition]['tipo_prodintermex'] = $this->formatoAlNombreDeLaAutorizacion($fus_cuerpo, $content['fus_configuracion_autorizaciones_id'], $validacionAutRech, 0);
                        break;
                    case 16:
                    case 64:
                        $data[$countPosition]['rol_mod_rep_formateado'] = $content['rol_mod_rep'];
                        $data[$countPosition]['tipo_prodintermex'] = $this->formatoAlNombreDeLaAutorizacion($fus_cuerpo, $content['fus_configuracion_autorizaciones_id'], $validacionAutRech, 0);
                        break;
                    default:
                        $data[$countPosition]['rol_mod_rep_formateado'] = $data[$countPosition]['rol_mod_rep'];
                        $data[$countPosition]['tipo_prodintermex'] = '';
                        break;
                }
            }
            
            $sendData['noEmp'] = $content['no_empleado_labora'];
            $sendData['tipo_autorizador'] = $content['tipo_autorizador'];
            $autRech = $validacionAutRech->validarAutorizacionORechazo($sendData);
            
            if($autRech['autorizaciones'] == 1) {
                $data[$countPosition]['estadoActualG'] = 1;
            } elseif ($autRech['rechazos'] == 1) {
                $data[$countPosition]['estadoActualG'] = 2;
            } else {
                $data[$countPosition]['estadoActualG'] = 0;
            }

            $data[$countPosition]['nombre_app'] = $FusSysadminController->appsTitulos($data[$countPosition]['applications_id']);
            
            switch($data[$countPosition]['tipo_autorizador']) {
                case 1:
                    switch ($content['applications_id']) {
                        case 20:
                        case 16:
                        case 64:
                            $dataReOrdenado['mesas'][$content['applications_id']][$data[$countPosition]['tipo_prodintermex']][] = $data[$countPosition];
                            break;
                        
                        default:
                            $dataReOrdenado['mesas'][$content['applications_id']][] = $data[$countPosition];
                            break;
                    }
                    break;
                case 2:
                    switch ($content['applications_id']) {
                        case 20:
                        case 16:
                        case 64:
                            $dataReOrdenado['autorizadores'][$content['applications_id']][$data[$countPosition]['tipo_prodintermex']][] = $data[$countPosition];
                            break;
                        
                        default:
                            $dataReOrdenado['autorizadores'][$content['applications_id']][] = $data[$countPosition];
                            break;
                    }
                    break;
                case 3:
                    switch ($content['applications_id']) {
                        case 20:
                        case 16:
                        case 64:
                            $dataReOrdenado['ratificadores'][$content['applications_id']][$data[$countPosition]['tipo_prodintermex']][] = $data[$countPosition];
                            break;
                        
                        default:
                            $dataReOrdenado['ratificadores'][$content['applications_id']][] = $data[$countPosition];
                            break;
                    }
                    break;
            }
            
            $countPosition = $countPosition+1;
        }

        // Calculo
        $checks = $this->calculoParaEnvioDeMail($datos['idFus']);
        $checksNotis = array();

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
        // Fin
        foreach($checks['calculocompleto'] AS $row) {
            $checksNotis['calculocompleto'][$row['rol_mod_rep']] = $row;
        }

        $fus = $getEncabezadoFus->getFusInfProcesada($request->id);
        // $appsNoBajas = array();
        // foreach($apps AS $appInd => $appsVal) {
        //     if($appsVal["tipo_movimiento"] != 3) {
        //         $appsNoBajas[$appInd] = $appsVal;
        //     }
        // }
        return view('fus.fusarevizar')->with(['fus' => $fus, 'objetosAutorizar' => $dataReOrdenado, 'apps' => $apps, 'idFus' => $datos['idFus'], "checks" => $checksNotis, "descargas" => $mostrarAnexos]);
    }

    public function guardarautorizaciones(Request $request) {
        $autorizacion = new RelConfigurationfussyswtl;
        $bitacora = new LogBookMovements;
        $notificaciones = new NotificacionesController;
        $RelFusApps = new RelFusApps;
        $apps = new Applications;

        if ($request->file('archivo')) {
            $anexo = new RelAnexosFus;
            $filePost = $request->post('archivo');
            $files = $request->file('archivo');
            foreach ($files as $key => $value) {
                $app = $filePost[$key]['app'];
                if(isset($value['file']))
                {
                    $arch = $value['file'];
                    $ext = $value['file']->getClientOriginalExtension();// recuperamos la extension del archivo
                    $filename  = 'anexo_' .$app.'_'.$request->post('idFus').'_'.time().rand().'.' . $ext;
                    $path = $arch->storeAs('anexos', $filename);
                    $an = $anexo->guardar($filename, $request->post('idFus'), $app);
                }
            }
        }
        
        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $idApps = array();
        $autorizaciones = $request->post('autorizacion');
        
        if($request->post('autorizacion') != null) {
            foreach($autorizaciones AS $row => $content) {
                foreach($content AS $objetos) {
                    foreach($objetos AS $index => $value) {
                        $nombreObjeto = $autorizacion->getInfoCompletaById($request->post('idFus'), $index);
                        $idApps[] = $nombreObjeto['applications_id'];
                        $accion = "";
                        if(isset($value['accion'])) {
                            switch($value['accion']) {
                                case 1:
                                    $accion = "la autorización";
                                    break;
                                
                                case 2:
                                    $accion = "el rechazo";
                                    break;
                            }

                            $autorizacion->cambiarestadoAutorizacion($index, $value, $request->post('idFus'));
                            $data = array(
                                'ip_address' => $this->ip_address_client, 
                                'description' => 'Empleado con #'.$noEmployee.' ha realizado '.$accion.' de '.$nombreObjeto['rol_mod_rep'].' en el FUS-e #'.$request->post('idFus'),
                                'tipo' => 'modificacion',
                                'id_user' => $idEmployee
                            );
                            
                            $bitacora->guardarBitacora($data);
                        }
                    }
                }
            }

            $checks = $this->calculoParaEnvioDeMail($request->post('idFus'));
            
            $checksNotis = array();

            foreach ($checks as $tipo => $value) {
                foreach($value AS $idapp => $content) {
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
            $mailsUsados = array();
            foreach($idApps AS $index => $value){
                $configFusAut = $autorizacion->getSetupForTracking($request->post('idFus'), $value, 2);
                $configFusRat = $autorizacion->getSetupForTracking($request->post('idFus'), $value, 3);
                if($value != null) {
                    if($checksNotis['mesas'][$value] > 0 && $checksNotis['autorizadores'][$value] == 0) {
                        foreach($configFusAut AS $ind => $val) {
                            if(!in_array($val, $mailsUsados)) {
                                $notificaciones->sendNuevoMailAutorizacionApps($request->post('idFus'), $val);
                            }
                            $mailsUsados[] = $val;
                        }
                    }
                    if(
                        $checksNotis['mesas'][$value] >= 1 && 
                        $checksNotis['autorizadores'][$value] >= 1 &&
                        $checksNotis['ratificadores'][$value] == 0
                    ) {
                        foreach($configFusRat AS $ind => $val) {
                            if(!in_array($val, $mailsUsados)) {
                                $notificaciones->sendNuevoMailAutorizacionApps($request->post('idFus'), $val);
                            }
                            $mailsUsados[] = $val;
                        }
                    }
                }
            }
            

            $evaluarCambioEstadoApp = array();
            
            foreach($checks['calculocompleto'] AS $row) {
                $nombreApp = $apps->getNameApplicationById($row['applications_id']);
                $evaluarCambioEstadoApp['calculocompleto'][$nombreApp][] = $row;
            }

            $terminadoApp = array();
            $estadoAppTemp = array();
            $estadoApp = array();
            foreach($evaluarCambioEstadoApp['calculocompleto'] AS $row => $value) {
                $terminado = array();

                foreach($value AS $fila) {
                    
                    if($fila['autorizados_mesas'] > 0) {
                        $terminado[$fila['rol_mod_rep']]['mesa'] = 1;
                    } else if($fila['rechazados_mesas'] > 0) {
                        $terminado[$fila['rol_mod_rep']]['mesa'] = 1;
                    } else {
                        if($fila['conteo_mesas'] > 0) {
                            $terminado[$fila['rol_mod_rep']]['mesa'] = 0;
                        } else {
                            $terminado[$fila['rol_mod_rep']]['mesa'] = '';
                        }
                    }

                    if($fila['autorizados_autorizadores'] > 0) {
                        $terminado[$fila['rol_mod_rep']]['autorizador'] = 1;
                    } else if($fila['rechazados_autorizadores'] > 0) {
                        $terminado[$fila['rol_mod_rep']]['autorizador'] = 1;
                    } else {
                        if($fila['conteo_autorizadores'] > 0) {
                            $terminado[$fila['rol_mod_rep']]['autorizador'] = 0;
                        } else {
                            $terminado[$fila['rol_mod_rep']]['autorizador'] = '';
                        }
                    }

                    if($fila['autorizados_ratificadores'] > 0) {
                            $terminado[$fila['rol_mod_rep']]['ratificador'] = 1;
                    } else if($fila['rechazados_ratificadores'] > 0) {
                            $terminado[$fila['rol_mod_rep']]['ratificador'] = 1;
                    } else {
                        if($fila['conteo_ratificadores'] > 0) {
                            $terminado[$fila['rol_mod_rep']]['ratificador'] = 0;
                        } else {
                            $terminado[$fila['rol_mod_rep']]['ratificador'] = '';
                        }
                    }

                    if(
                        $fila['autorizados_mesas'] > 0 &&
                        $fila['autorizados_autorizadores'] > 0 &&
                        $fila['autorizados_ratificadores'] > 0 &&
                        $fila['conteo_mesas'] > 0 &&
                        $fila['conteo_autorizadores'] > 0 &&
                        $fila['conteo_ratificadores'] > 0 ||

                        $fila['autorizados_autorizadores'] > 0 &&
                        $fila['autorizados_ratificadores'] > 0 &&
                        $fila['conteo_mesas'] == 0 &&
                        $fila['conteo_autorizadores'] > 0 &&
                        $fila['conteo_ratificadores'] > 0 ||

                        $fila['autorizados_mesas'] > 0 &&
                        $fila['autorizados_ratificadores'] > 0 &&
                        $fila['conteo_mesas'] > 0 &&
                        $fila['conteo_autorizadores'] == 0 &&
                        $fila['conteo_ratificadores'] > 0 ||

                        $fila['autorizados_mesas'] > 0 &&
                        $fila['autorizados_autorizadores'] > 0 &&
                        $fila['conteo_mesas'] > 0 &&
                        $fila['conteo_autorizadores'] > 0 &&
                        $fila['conteo_ratificadores'] == 0 ||
// En caso de un solo agente
                        // Mesa
                        $fila['autorizados_mesas'] > 0 &&
                        $fila['conteo_mesas'] > 0 &&
                        $fila['conteo_autorizadores'] == 0 &&
                        $fila['conteo_ratificadores'] == 0 ||                

                        // Ratificador
                        $fila['autorizados_ratificadores'] > 0 &&
                        $fila['conteo_mesas'] == 0 &&
                        $fila['conteo_autorizadores'] == 0 &&
                        $fila['conteo_ratificadores'] > 0 ||
                        
                        // Autorizador
                        $fila['autorizados_autorizadores'] > 0 &&
                        $fila['conteo_mesas'] == 0 &&
                        $fila['conteo_autorizadores'] > 0 &&
                        $fila['conteo_ratificadores'] == 0
                    ) {
                        $estadoAppTemp[$fila['applications_id']][$fila['rol_mod_rep']] = 1; // Autorizado
                    } else if(
                        $fila['rechazados_mesas'] > 0 ||
                        $fila['rechazados_autorizadores'] > 0 ||
                        $fila['rechazados_ratificadores'] > 0 
                    ) {
                        $estadoAppTemp[$fila['applications_id']][$fila['rol_mod_rep']] = 2; // Rechazado
                    } else {
                        $estadoAppTemp[$fila['applications_id']][$fila['rol_mod_rep']] = 3; // P. Aut.
                    }

                    if(
                        $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['ratificador'] == 1 ||

                        $terminado[$fila['rol_mod_rep']]['mesa'] == '' &&
                        $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['ratificador'] == 1 ||

                        $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['autorizador'] == '' &&
                        $terminado[$fila['rol_mod_rep']]['ratificador'] == 1 ||

                        $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['ratificador'] == '' ||

                        $terminado[$fila['rol_mod_rep']]['mesa'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['autorizador'] == '' &&
                        $terminado[$fila['rol_mod_rep']]['ratificador'] == '' ||

                        $terminado[$fila['rol_mod_rep']]['mesa'] == '' &&
                        $terminado[$fila['rol_mod_rep']]['autorizador'] == 1 &&
                        $terminado[$fila['rol_mod_rep']]['ratificador'] == '' ||

                        $terminado[$fila['rol_mod_rep']]['mesa'] == '' &&
                        $terminado[$fila['rol_mod_rep']]['autorizador'] == '' &&
                        $terminado[$fila['rol_mod_rep']]['ratificador'] == 1
                    ) {
                        $terminadoApp[$fila['applications_id']][$fila['rol_mod_rep']] = 1;
                    } else {
                        $terminadoApp[$fila['applications_id']][$fila['rol_mod_rep']] = 0;
                    }
                }
            }
            
            foreach($estadoAppTemp AS $key => $valor) {
                $rechazos = 0;
                $autorizados = 0;
                $pauto = 0;
                $totalObj = count($valor);
                foreach($valor AS $k => $v) {
                    if($v == 1) {
                        $autorizados = $autorizados+1;
                    } else if($v == 2) {
                        $rechazos = $rechazos+1;
                    } else if($v == 3) {
                        $pauto = $pauto+1;
                    }
                }
                // 0 - Pendiente
                // 1 - Rechazado
                // 2 - Autorizado
                // 3 - Atendido
                // 4 - Parcialmente Autorizado
                if(
                    $autorizados > 0 &&
                    $rechazos > 0 &&
                    $totalObj == ($autorizados+$rechazos)
                ) {
                    $estadoApp[$key] = 4;
                } else if(
                    $autorizados > 0 &&
                    $totalObj == $autorizados &&
                    $rechazos == 0
                ) {
                    $estadoApp[$key] = 2;
                } elseif(
                    $autorizados == 0 &&
                    $rechazos > 0 &&
                    $totalObj == $rechazos
                ) {
                    $estadoApp[$key] = 1;
                } elseif ($pauto > 0) {
                    $estadoApp[$key] = 0;
                }
            }
            
            if(count($estadoApp) > 0) {
                foreach($terminadoApp AS $r => $e) {
                    $total = count($e);
                    $obtenidos = 0;
                    foreach($e AS $ra => $te) {
                        if($te == 1) {
                            $obtenidos = $obtenidos+1;
                        }
                    }
                    if($obtenidos == $total) {
                        $RelFusApps->automaticoCambioEstadoApp($r, $request->post('idFus'), $estadoApp[$r]);
                    }
                }
            }
            
            $msjOk = 1;
        } else {
            $msjOk = 2;
        }

        return redirect()->route('listafusesporautorizar', ['msjOk' => $msjOk]);
    }

    public function obtenerNecesarios($tipoAut) {
        $total = 0;
        foreach($tipoAut AS $index => $value) {
            foreach($value AS $ind => $val) {
                if($val == 1) {
                    $total = $total+1;
                }
            }
        }

        return $total;
    }

    public function cambirEstadoALaApp($checks) {

        $verificarAtendidos = array();
        $obtenerElTotal = array();
        $newChecks = array();

        foreach($checks['mesas'][$checks['calculocompleto'][0]['applications_id']] AS $ind => $val) {
            if($val == 1) {
                if(!isset($obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['mesas'])) {
                   $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['mesas'] = $val;
                } else {
                   $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['mesas'] =$obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['mesas']+$val;
                }
            }
        }

        if(!isset($obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['mesas'])) {
            $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['mesas'] = 0;
        }

        foreach($checks['autorizadores'][$checks['calculocompleto'][0]['applications_id']] AS $ind => $val) {
            if($val == 1) {
                if(!isset($obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['autorizadores'])) {
                   $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['autorizadores'] = $val;
                } else {
                   $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['autorizadores'] =$obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['autorizadores']+$val;
                }
            }
        }

        if(!isset($obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['autorizadores'])) {
            $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['autorizadores'] = 0;
        }

        foreach($checks['ratificadores'][$checks['calculocompleto'][0]['applications_id']] AS $ind => $val) {
            if($val == 1) {
                if(!isset($obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['ratificadores'])) {
                   $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['ratificadores'] = $val;
                } else {
                   $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['ratificadores'] =$obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['ratificadores']+$val;
                }
            }
        }

        if(!isset($obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['ratificadores'])) {
            $obtenerElTotal[$checks['calculocompleto'][0]['applications_id']]['totales']['ratificadores'] = 0;
        }

        foreach($checks['calculocompleto'] AS $index => $value) {    
            if(!isset($obtenerElTotal[$value['applications_id']]['totales']["atendidos_mesas"])){
                $obtenerElTotal[$value['applications_id']]['totales']["atendidos_mesas"] = $value["atendidos_mesas"];    
            } else {
                $obtenerElTotal[$value['applications_id']]['totales']["atendidos_mesas"] = $obtenerElTotal[$value['applications_id']]['totales']["atendidos_mesas"]+$value["atendidos_mesas"];
            }
            if(!isset($obtenerElTotal[$value['applications_id']]['totales']["atendidos_autorizadores"])){
                $obtenerElTotal[$value['applications_id']]['totales']["atendidos_autorizadores"] = $value["atendidos_autorizadores"];    
            } else {
                $obtenerElTotal[$value['applications_id']]['totales']["atendidos_autorizadores"] = $obtenerElTotal[$value['applications_id']]['totales']["atendidos_autorizadores"]+$value["atendidos_autorizadores"];
            }
            if(!isset($obtenerElTotal[$value['applications_id']]['totales']["atendidos_ratificadores"])){
                $obtenerElTotal[$value['applications_id']]['totales']["atendidos_ratificadores"] = $value["atendidos_ratificadores"];    
            } else {
                $obtenerElTotal[$value['applications_id']]['totales']["atendidos_ratificadores"] = $obtenerElTotal[$value['applications_id']]['totales']["atendidos_ratificadores"]+$value["atendidos_ratificadores"];
            }
            if(
                $value["conteo_mesas"] == $value["atendidos_mesas"] &&
                $value["conteo_autorizadores"] == $value["atendidos_autorizadores"] &&
                $value["conteo_ratificadores"] == $value["atendidos_ratificadores"]
            ) {
                if(!isset($verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['autorizados_mesas'])) {
                    $verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['autorizados_mesas'] = $value['autorizados_mesas'];
                }
                if(!isset($verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['autorizados_autorizadores'])) {
                    $verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['autorizados_autorizadores'] = $value['autorizados_autorizadores'];
                }
                if(!isset($verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['autorizados_ratificadores'])) {
                    $verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['autorizados_ratificadores'] = $value['autorizados_ratificadores'];
                }
                if(!isset($verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['rechazados_mesas'])) {
                    $verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['rechazados_mesas'] = $value['rechazados_mesas'];
                }
                if(!isset($verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['rechazados_autorizadores'])) {
                    $verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['rechazados_autorizadores'] = $value['rechazados_autorizadores'];
                }
                if(!isset($verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['rechazados_ratificadores'])) {
                    $verificarAtendidos[$value['applications_id']][$value['rol_mod_rep']]['rechazados_ratificadores'] = $value['rechazados_ratificadores'];
                }
            }
        }

        foreach ($verificarAtendidos as $key => $value) {
            foreach($value AS $row => $content) {
                if(
                    $content['rechazados_mesas'] > 0 ||
                    $content['rechazados_autorizadores'] > 0 ||
                    $content['rechazados_ratificadores'] > 0
                ) {
                    $obtenerElTotal[$key]['atendido'][$row]['rechazo'] = 1;
                } else {
                    $obtenerElTotal[$key]['atendido'][$row]['autorizacion'] = 1;
                }
            }    
        }

        return $obtenerElTotal;
    }

    public function terminadoApp($autorizacion) {
        $autorizacionApp = array();
        foreach ($autorizacion as $key => $value) {
            if(
                $value['totales']['conteo_mesas'] == $value['totales']['atendidos_mesas'] &&
                $value['totales']['conteo_autorizadores'] == $value['totales']['atendidos_autorizadores'] &&
                $value['totales']['conteo_ratificadores'] == $value['totales']['atendidos_ratificadores']
            ) {
                foreach($value['atendido'] AS $row => $content) {
                    if(
                        isset($content['autorizacion']) &&
                        $content['autorizacion'] == 1
                    ) {
                        if(isset($autorizacionApp[$key]['autorizaciones'])) {
                            $autorizacionApp[$key]['autorizaciones'] = $content['autorizacion']+1;
                        } else {
                            $autorizacionApp[$key]['autorizaciones'] = 1;
                        }
                    } else if(
                        isset($content['rechazo']) &&
                        $content['rechazo'] == 1
                    ) {
                        if(isset($autorizacionApp[$key]['rechazos'])) {
                            $autorizacionApp[$key]['rechazos'] = $content['rechazo']+1;
                        } else {
                            $autorizacionApp[$key]['rechazos'] = 1;
                        }
                    }
                }
            }
        }

        return $autorizacionApp;
    }

    public function calculoParaEnvioDeMail($idFus) {
        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;

        $fuses = new RelConfigurationfussyswtl;
        $validacionAutRech = new FusConfiguracionesAutorizaciones;

        $datos = array();
        $datos['idFus'] = $idFus;
        $datos['noEmp'] = $noEmployee;
        $apps = $fuses->getAppsOfFus($datos['idFus']);
        
        $calculoTotalPorObjeto = $fuses->calculoTotal($datos['idFus']);

        $conteoMesas = array();
        $conteoAutorizadores = array();
        $conteoRatificadores = array();

        foreach($apps AS $row => $content) {
            foreach($calculoTotalPorObjeto AS $index => $value) {
                if($value['applications_id'] == $content['applications_id']) {
                    if($value['atendidos_mesas'] > 0 && isset($content['rol_mod_rep']) && $value['rol_mod_rep'] == $content['rol_mod_rep']) {
                        $conteoMesas[$content['applications_id']] = (isset($conteoMesas[$content['applications_id']])) ? $conteoMesas[$content['applications_id']]+1 : 1;
                    }
                    if($value['atendidos_autorizadores'] > 0 && isset($content['rol_mod_rep']) && $value['rol_mod_rep'] == $content['rol_mod_rep']) {
                        $conteoAutorizadores[$content['applications_id']] = (isset($conteoAutorizadores[$content['applications_id']])) ? $conteoAutorizadores[$content['applications_id']]+1 : 1;
                    }
                    if($value['atendidos_ratificadores'] > 0 && isset($content['rol_mod_rep']) && $value['rol_mod_rep'] == $content['rol_mod_rep']) {
                        $conteoRatificadores[$content['applications_id']] = (isset($conteoRatificadores[$content['applications_id']])) ? $conteoRatificadores[$content['applications_id']]+1 : 1;
                    }
                }
            }
        }

        $checkMesas = array();
        $checkAutorizadores = array();
        $checkRatificadores = array();
        
        foreach($calculoTotalPorObjeto AS $index => $value) {
            if($value['conteo_mesas'] > 0 && $value['atendidos_mesas'] > 0) {
                $checkMesas[$value['applications_id']][$value['rol_mod_rep']] = 1;
            } elseif($value['conteo_mesas'] > 0 && $value['atendidos_mesas'] == 0){
                $checkMesas[$value['applications_id']][$value['rol_mod_rep']] = 0;
            } elseif($value['conteo_mesas'] == 0){
                $checkMesas[$value['applications_id']][$value['rol_mod_rep']] = '-1';
            }
            
            if($value['conteo_autorizadores'] > 0 && $value['atendidos_autorizadores'] > 0) {
                $checkAutorizadores[$value['applications_id']][$value['rol_mod_rep']] = 1;
            } elseif($value['conteo_autorizadores'] > 0 && $value['atendidos_autorizadores'] == 0){
                $checkAutorizadores[$value['applications_id']][$value['rol_mod_rep']] = 0;
            } elseif($value['conteo_autorizadores'] == 0){
                $checkAutorizadores[$value['applications_id']][$value['rol_mod_rep']] = '-1';
            }

            if($value['conteo_ratificadores'] > 0 && $value['atendidos_ratificadores'] > 0) {
                $checkRatificadores[$value['applications_id']][$value['rol_mod_rep']] = 1;
            } elseif($value['conteo_ratificadores'] > 0 && $value['atendidos_ratificadores'] == 0){
                $checkRatificadores[$value['applications_id']][$value['rol_mod_rep']] = 0;
            } elseif($value['conteo_ratificadores'] == 0){
                $checkRatificadores[$value['applications_id']][$value['rol_mod_rep']] = '-1';
            }
        }
        
        $checks = array("mesas" =>$checkMesas, "autorizadores" => $checkAutorizadores, "ratificadores" => $checkRatificadores, 'calculocompleto' => $calculoTotalPorObjeto);

        return $checks;
    }
}
