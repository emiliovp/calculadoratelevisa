<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AutoSolGeneral;
use App\Mail\AutoAppJefeAut;
use App\Mail\AutorizacionApps;
use App\Mail\AutorizacionAppsNuevo;
use App\Mail\RechazoFusJefeAut;
use App\Mail\FusAutorizado;
use App\Mail\NotFinalWtl;
use App\Mail\notificacionAsignacionAutorizacion;
use App\Http\Controllers\FusAutorizacionesController;
use App\LogBookMovements;
use App\SolicitudModelo;
use App\RelConfigurationfussyswtl;
use App\ObservacionPorRechazo;
use App\FusConfigAutOtro;

use App\Http\Controllers;
use App\Http\Controllers\FusGeneralController;

class NotificacionesController extends Controller
{
    public $ip_address_client;

    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
    }
    public function sendMailNotificacionSolicitante($id){
        $getFus = new SolicitudModelo;
        $fus = $getFus->getSolById($id);
        /*switch ($fus['tipo_fus']) {
            case 0:
                $tipo_fus = 'Aplicaciones';
                break;
            case 1:
                $tipo_fus = 'Usuario de Red';
                break;
            case 2:
                $tipo_fus = 'Usuario de Correo';
                break;
            case 3:
                $tipo_fus = 'Usuario de Correo Especial';
                break;
            case 4:
                $tipo_fus = 'Usuario de Acceso a una Carpeta o Direcotrio';
                break;
            case 5:
                $tipo_fus = 'Usuario de Acceso a la Red por VPN';
                break;
            case 6:
                $tipo_fus = 'Usuario de Acceso a la Red Corporativa';
                break;
            }*/
        $tipo_fus = 'Solicitud de equipo de computo';
        $correosFus = $fus['cal_correo'];
        $emails = array($correosFus);
        $mail = Mail::to($emails);
        $mail->send(new AutoSolGeneral($tipo_fus, $id));
        /*$data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Envío de correo de notificacion al solicitante #'.$id,
            'tipo' => 'sendMail',
            'id_user' => 1
        );
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);*/ 
    }

    public function notificacionAsginacionConfig($data) {
        foreach($data AS $mail => $info) {
            $email = Mail::to($mail);
            $email->send(new notificacionAsignacionAutorizacion($info));
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Envío de correo de notificacion a empleado asignado en un objeto de autorización',
            'tipo' => 'sendMail',
            'id_user' => 1
        );
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data); 
    }

    public function sendMailAutorizacionJefe($id, $jefeOAut) {
        $getFus = new SolicitudModelo;
        $fus = $getFus->getSolById($id);
        /*switch ($fus['tipo_fus']) {
            case 0:
                $tipo_fus = 'Aplicaciones';
                break;
            case 1:
                $tipo_fus = 'Usuario de Red';
                break;
            case 2:
                $tipo_fus = 'Usuario de Correo';
                break;
            case 3:
                $tipo_fus = 'Usuario de Correo Especial';
                break;
            case 4:
                $tipo_fus = 'Usuario de Acceso a una Carpeta o Direcotrio';
                break;
            case 5:
                $tipo_fus = 'Usuario de Acceso a la Red por VPN';
                break;
            case 6:
                $tipo_fus = 'Usuario de Acceso a la Red Corporativa';
                break;
        }*/
        $tipo_fus = 'Solicitud de equipo de computo';
        switch ($jefeOAut) {
            case 1:
                $correosFus = $fus['cal_correo_jefe'];
                break;
            case 2:
                $correosFus = $fus['cal_aut_correo'];
                break;
        }

        $emails = array($correosFus);

        $mail = Mail::to($emails);
        // $jefeOAut = 1 Jefe
        // $jefeOAut = 2 Autorizador
        // $jefeOAut = 3 Autorizador Apps
        // $jefeOAut = 4 Autorizador Otros
        $mail->send(new AutoAppJefeAut($tipo = 1, $id, $jefeOAut, $tipo_fus));

        /*$data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Envío de correo de autorización del FUS #'.$id,
            'tipo' => 'sendMail',
            'id_user' => 1
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);*/  
    }

    public function sendMailRechazoSolicitante($idFus, $idObse, $jefeOAut = null, $rechazoApp = null) {
        $fus = new FUSSysadminWtl;
        $infoFus = $fus->getFusById($idFus);
        switch ($infoFus['tipo_fus']) {
            case 0:
                $tipo_fus = 'Aplicaciones';
                break;
            case 1:
                $tipo_fus = 'Usuario de Red';
                break;
            case 2:
                $tipo_fus = 'Usuario de Correo';
                break;
            case 3:
                $tipo_fus = 'Usuario de Correo Especial';
                break;
            case 4:
                $tipo_fus = 'Usuario de Acceso a una Carpeta o Directorio';
                break;
            case 5:
                $tipo_fus = 'Usuario de Acceso a la Red por VPN';
                break;
            case 6:
                $tipo_fus = 'Usuario de Acceso a la Red Corporativa';
                break;
        }

        $correosFus = $infoFus['correo_corporativo'];

        $observaciones = new ObservacionPorRechazo;
        
        $infoObse = $observaciones->getObservacionById($idObse);
        
        $emails = array($correosFus);
        
        $mail = Mail::to($emails);
        $mail->send(new RechazoFusJefeAut($idFus, $infoObse['observacion'], $jefeOAut, $rechazoApp, $tipo_fus));

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Envío de correo de rechazo del FUS #'.$idFus,
            'tipo' => 'sendMail',
            'id_user' => 1
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
    }

    public function sendMailAutorizacionApps($idFus, /*$idapp = null, */$idRelConfig = null) {
        $fus = new FUSSysadminWtl;
        $infoFus = $fus->getFusById($idFus);
        
        switch ($infoFus['tipo_fus']) {
            case 0:
                $tipo_fus = 'Aplicaciones';
                break;
            case 1:
                $tipo_fus = 'Usuario de Red';
                break;
            case 2:
                $tipo_fus = 'Usuario de Correo';
                break;
            case 3:
                $tipo_fus = 'Usuario de Correo Especial';
                break;
            case 4:
                $tipo_fus = 'Usuario de Acceso a una Carpeta o Directorio';
                break;
            case 5:
                $tipo_fus = 'Usuario de Acceso a la Red por VPN';
                break;
            case 6:
                $tipo_fus = 'Usuario de Acceso a la Red Corporativa';
                break;
        }
        
        if(
            $infoFus['autorizo_jefe'] == 1 &&
            $infoFus['no_empleado_aut'] == "" ||
            $infoFus['autorizo_jefe'] == 1 &&
            $infoFus['no_empleado_aut'] != "" &&
            $infoFus['aut_autorizo'] == 1
        ) {
            switch ($infoFus['tipo_fus']) {
                case 0:
                    $autoConfig = new RelConfigurationfussyswtl;
                    $correosFus = array();
                    
                    // if($idapp == null || empty($idapp)) {
                    $calculo = $autoConfig->verificarTiposAutorizadores($infoFus['id']);

                    foreach($calculo AS $index => $autorizaciones) {
                        if($autorizaciones['mesa'] > 0) {
                            array_push($correosFus, $autoConfig->getMailsByIdFUSMesaAutRat($infoFus['id'], 1, $autorizaciones['applications_id'], null));
                        } else if($autorizaciones['mesa'] == 0 && $autorizaciones['autorizador'] > 0) {
                            array_push($correosFus, $autoConfig->getMailsByIdFUSMesaAutRat($infoFus['id'], 2, $autorizaciones['applications_id'], null));
                        } else if($autorizaciones['mesa'] == 0 && $autorizaciones['autorizador'] == 0 && $autorizaciones['ratificador'] > 0) {
                            array_push($correosFus, $autoConfig->getMailsByIdFUSMesaAutRat($infoFus['id'], 3, $autorizaciones['applications_id'], null));
                        }
                    }
                    // } else {
                    //     dd($idFus, $idapp, $idRelConfig);
                    //     $calculo = $autoConfig->verificarTiposAutorizadoresByIdApp($idFus, $idapp, $idRelConfig);
                    //     if($tiposAutorizadores['mesa'] == 0) {
                    //         array_push($correosFus, $autoConfig->getMailsByIdFUSMesaAutRat($infoFus['id'], 1, $idapp, $idRelConfig));
                    //     } else if(
                    //         $tiposAutorizadores['mesa'] == 1 && $tiposAutorizadores['autorizador'] == 0 || 
                    //         $tiposAutorizadores['mesa'] == "-1" && $tiposAutorizadores['autorizador'] == 0
                    //     ) {
                    //         array_push($correosFus, $autoConfig->getMailsByIdFUSMesaAutRat($infoFus['id'], 2, $idapp, $idRelConfig));
                    //     } else if(
                    //         $tiposAutorizadores['mesa'] == 1 && $tiposAutorizadores['autorizador'] == 1 && $tiposAutorizadores['ratificador'] == 0 ||
                    //         $tiposAutorizadores['mesa'] == 1 && $tiposAutorizadores['autorizador'] == "-1" && $tiposAutorizadores['ratificador'] == 0 ||
                    //         $tiposAutorizadores['mesa'] == "-1" && $tiposAutorizadores['autorizador'] == "-1" && $tiposAutorizadores['ratificador'] == 0 ||
                    //         $tiposAutorizadores['mesa'] == "-1" && $tiposAutorizadores['autorizador'] == 1 && $tiposAutorizadores['ratificador'] == 0
                    //     ) {
                    //         array_push($correosFus, $autoConfig->getMailsByIdFUSMesaAutRat($infoFus['id'], 3, $idapp, $idRelConfig));
                    //     }
                    // }
                    
                    $act_Apps_Otros = 0; // indica que es tipo apps
                    $jefeOAut = 3;
                    break;
                case 4:
                case 5:
                case 6:
                    $autoConfig = new FusConfigAutOtro;
                    $correosFus = $autoConfig->getConfiguracionAutorizacionesByIdFUS($infoFus['id']);
                    $act_Apps_Otros = 1; // indica que es tipo otros
                    $jefeOAut = 4;
                    break;
            } 
            
            // $jefeOAut = 1 Jefe
            // $jefeOAut = 2 Autorizador
            // $jefeOAut = 3 Autorizador Apps
            // $jefeOAut = 4 Autorizador Otros
            if (isset($correosFus)) {
                foreach($correosFus AS $keys => $value) {
                    if($jefeOAut == 3) {
                        foreach ($value as $row) {
                            $mail = Mail::to($row['correo']);
                            $mail->send(new AutorizacionApps($row['applications_id'], $tipo = 1, $idFus, $jefeOAut, $row['idRelConf'], $act_Apps_Otros, $tipo_fus));
                            $mail = null;
                        }
                    } else {
                        // Podría cambiar
                        $mail = Mail::to($value['correo']);
                        $mail->send(new AutorizacionApps($tipo = 1, $idFus, $jefeOAut, $value['idRelConf'], $act_Apps_Otros, $tipo_fus));
                    }
                    
                    $data = array(
                        'ip_address' => $this->ip_address_client, 
                        'description' => 'Envío de correo de autorización del FUS #'.$idFus,
                        'tipo' => 'sendMail',
                        'id_user' => 1
                    );
            
                    $bitacora = new LogBookMovements;
                    $bitacora->guardarBitacora($data);
                }
            }
        }
    }

    public function reenvioDeNotificaciones($id) {
        $getFus = new FUSSysadminWtl;
        $fus = $getFus->getFusById($id);
        switch ($fus['tipo_fus']) {
            case 0:
                $tipo_fus = 'Aplicaciones';
                break;
            case 1:
                $tipo_fus = 'Usuario de Red';
                break;
            case 2:
                $tipo_fus = 'Usuario de Correo';
                break;
            case 3:
                $tipo_fus = 'Usuario de Correo Especial';
                break;
            case 4:
                $tipo_fus = 'Usuario de Acceso a una Carpeta o Direcotrio';
                break;
            case 5:
                $tipo_fus = 'Usuario de Acceso a la Red por VPN';
                break;
            case 6:
                $tipo_fus = 'Usuario de Acceso a la Red Corporativa';
                break;
        }
        // $jefeOAut = 1 Jefe
        // $jefeOAut = 2 Autorizador
        // $jefeOAut = 3 Autorizador Apps
        // $jefeOAut = 4 Autorizador Otros
        if(!empty($fus['correo_jefe']) && $fus['autorizo_jefe'] == 0) {
            $correosFus = $fus['correo_jefe'];
            $emails = array($correosFus);
            $mail = Mail::to($emails);
            $mail->send(new AutoAppJefeAut($tipo = 1, $id, 1, $tipo_fus));    
        }

        if(!empty($fus['aut_correo']) && $fus['aut_autorizo'] == 0) {
            $correosFus = $fus['aut_correo'];
            $emails = array($correosFus);
            $mail = Mail::to($emails);
            $mail->send(new AutoAppJefeAut($tipo = 1, $id, 2, $tipo_fus));
        }

        
        /*********************************************************** */
        switch ($fus['tipo_fus']) {
            case 0:
                $autoConfig = new RelConfigurationfussyswtl;
                $correosFus = $autoConfig->getConfiguracionAutorizacionesByIdFUS($id);
                $act_Apps_Otros = 0; // indica que es tipo apps
                $jefeOAut = 3;
                break;
            case 4:
            case 5:
            case 6:
                $autoConfig = new FusConfigAutOtro;
                $correosFus = $autoConfig->getConfiguracionAutorizacionesByIdFUS($id);
                $act_Apps_Otros = 1; // indica que es tipo otros
                $jefeOAut = 4;
                break;
        }
        // Solo cuando los autorizadores del fus hayan autorizado
        if(
            !empty($fus['correo_jefe']) && 
            $fus['autorizo_jefe'] == 1 && 
            !empty($fus['aut_correo']) && 
            $fus['aut_autorizo'] == 1 ||
            !empty($fus['correo_jefe']) && 
            $fus['autorizo_jefe'] == 1 && 
            empty($fus['aut_correo'])
        ) {
        
            if(is_array($correosFus)) {
                foreach($correosFus AS $keys => $value) {
                    $mail = Mail::to($value['correo']);
        
                    // $jefeOAut = 1 Jefe
                    // $jefeOAut = 2 Autorizador
                    // $jefeOAut = 3 Autorizador Apps
                    // $jefeOAut = 4 Autorizador Otros
                    // $jefeOAut = 3;
                    $mail->send(new AutorizacionApps($value["applications_id"], $tipo = 1, $id, $jefeOAut, $value['idRelConf'], $act_Apps_Otros, $tipo_fus));
                }
            }
            /*********************************************************** */
            
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Envío de correo de autorización del FUS #'.$id,
                'tipo' => 'sendMail',
                'id_user' => 1
            );

            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);   
        }
    }
    public function fusAutorizado($id){
        $fus = new FUSSysadminWtl;
        $data = $fus->getFusByIdWtl($id);
        if ($data != null) {
            switch ($data['tipo_fus']) {
                case 0:
                    $tipo_fus = 'Aplicaciones';
                    break;
                case 1:
                    $tipo_fus = 'Usuario de Red';
                    break;
                case 2:
                    $tipo_fus = 'Usuario de Correo';
                    break;
                case 3:
                    $tipo_fus = 'Usuario de Correo Especial';
                    break;
                case 4:
                    $tipo_fus = 'Usuario de Acceso a una Carpeta o Direcotrio';
                    break;
                case 5:
                    $tipo_fus = 'Usuario de Acceso a la Red por VPN';
                    break;
                case 6:
                    $tipo_fus = 'Usuario de Acceso a la Red Corporativa';
                    break;
            }
            $correo = $data['correo_corporativo'];
            $mail = Mail::to($correo);
            $mail->send(new FusAutorizado($data["id"],$tipo_fus));
        }
    }
    public function not_fus_final(Request $request){
        $a = new FusGeneralController;
        $fus = new FUSSysadminWtl;
        $data = $fus->getFusByIdWtl($request->post('id'));
        
        if ($data != null) {
            $doc = $a->export($request->post('id'));
            $correo= array('Opservicedesk@televisa.com.mx','Cat@televisa.com.mx');
            $mail = Mail::to($correo);
            $mail->send(new NotFinalWtl($request->post('id'),$doc['nombre'], $doc['archivo']));
        }
    }

    public function sendNuevoMailAutorizacionApps($idFus, $correo) {
        $mail = Mail::to($correo);
        $mail->send(new AutorizacionAppsNuevo($idFus));
    }
}
