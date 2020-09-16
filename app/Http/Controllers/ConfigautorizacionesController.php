<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Catalogosmodel;
use App\Op_cat_model;
use App\FusConfiguracionesAutorizaciones;
use App\ActiveDirectoriActive;
use App\ActivedirectoryEmployees;
use App\LogBookMovements;
use App\Applications;
use App\MesaControl;
use App\ArchivoAnexo;
use App\FusUserLogin;
use App\ListaResponsabilidades;
use App\RelCatOpcionesConfiguracionesAutorizaciones;
use App\Http\Controllers\NotificacionesController;

use Yajra\Datatables\Datatables;
class ConfigautorizacionesController extends Controller
{
    public $ip_address_client;
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function index() {
        $con = new FusUserLogin;
        $idEmployee = $con->getIdByNameUser(Auth::user()->name);
        if($idEmployee == 0) {
            $idEmployee['id'] = null;
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de alta de configuraciones de autorizaciones',
            'tipo' => 'vista',
            'id_user' => $idEmployee['id']
        );
        
        $appsConfiguraciones = new FusConfiguracionesAutorizaciones;

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view('configautorizaciones.index')->with('appsConfig', $appsConfiguraciones->getConfiguraciones());
    }

    public function alta() {
        $con = new FusUserLogin;
        $idEmployee = $con->getIdByNameUser(Auth::user()->name);
        if($idEmployee == 0) {
            $idEmployee['id'] = null;
        }
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de alta de configuraciones de autorizaciones',
            'tipo' => 'vista',
            'id_user' => $idEmployee['id']
        );
        // dd(Auth::user());
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        $aplicaciones = new Applications;
        $mesas = new Catalogosmodel;
        
        return view('configautorizaciones.alta')->with(['aplicaciones' => $aplicaciones->getApplicationsRespCat(), 'mesasdecontrol' => $mesas->getMesasControl()]);
    }
    
    public function getcatalogoByIdApp(Request $request) {
        $catalogos = new Catalogosmodel;
        return $catalogos->getcatalogoByIdApp($request->post('id'));
    }

    public function getCatOpcionesByIdCat(Request $request) {
        $catalogos = new Op_cat_model;
        return $catalogos->getOptCatalogoByIdApp($request->post('id'));
    }

    public function store(Request $request) {

        $notificacion = new NotificacionesController;
        $applications = new Applications;
        $bitacora = new LogBookMovements;

        $con = new FusUserLogin;
        $idEmployee = $con->getIdByNameUser(Auth::user()->name);
        if($idEmployee == 0) {
            $idEmployee['id'] = null;
        }
        $dataHistorico = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se creo una nueva configuración de autorizaciones',
            'tipo' => 'alta',
            'id_user' => $idEmployee['id']
        );

        
        $bitacora->guardarBitacora($dataHistorico);

        $data = array();
        $sendMails = array();

        $post = $request->post();

        $count = 0;
        // dd($post);
        while($count < $post["total_registros"]) {
            $configSave = new FusConfiguracionesAutorizaciones;
            $anexo = new ArchivoAnexo;
            if($post["jsonOpcionesConfiguraciones"][$count] == "{}") {
                $jsonOpcionesConfiguraciones = null;
            } else {
                $jsonOpcionesConfiguraciones = json_decode(str_replace('"\"', '"', $post["jsonOpcionesConfiguraciones"][$count]), true);
            }

            $configSave->correo = $post["emailuser"][$count];
            $configSave->no_empleado_labora = $post["numemp"][$count];
            $configSave->nombre_labora = $post["nombreemp"][$count];
            $configSave->usuario_red = (isset($post["samaccountname"][$count])) ? strtolower($post["samaccountname"][$count]) : null;
            $configSave->tipo_autorizacion = $post["tipoautorizacion"][$count];
            $configSave->rol_mod_rep = $post["rolmodrep"][$count];
            $configSave->estado = 1;
            $configSave->applications_id = (isset($post["aplicaciones"][$count])) ? $post["aplicaciones"][$count] : null;
            $configSave->cat_opciones_id = (isset($post["idmsctl"][$count])) ? $post["idmsctl"][$count] : null;
            $configSave->tipo_autorizador = $post["tipo"][$count];
            $configSave->id_usr_alta = $idEmployee["id"];
            
            if($configSave->save()) {
                if(isset($jsonOpcionesConfiguraciones) && $jsonOpcionesConfiguraciones != null) {
                    foreach($jsonOpcionesConfiguraciones AS $indx => $val) {
                        $saveRel = new RelCatOpcionesConfiguracionesAutorizaciones;
                        $saveRel->guardarRelacion($configSave->id, $val['catalogoSubOpciones']);
                    }           
                }
            }

            $file = $request->file("archivo_".$count);

            $arch = $file;
            $ext = $file->getClientOriginalExtension();// recuperamos la extension del archivo
            $filename  = 'anexo_'.$configSave->id.'_'.time().rand().'.'.$ext;
            $path = $arch->storeAs('anexos', $filename);
            $an = $anexo->guardar($filename, $configSave->id, "responsabilidades");

            $sendMails[$configSave->correo][$configSave->id]["app"] = $applications->getNameApplicationById($configSave->applications_id);
            $sendMails[$configSave->correo][$configSave->id]["tipo_autorizador"] = $this->getTitleAsignacion(1, $configSave->tipo_autorizador);
            $sendMails[$configSave->correo][$configSave->id]["tipo_autorizacion"] = $this->getTitleAsignacion(2, $configSave->tipo_autorizacion);
            $sendMails[$configSave->correo][$configSave->id]["rol_mod_rep"] = $configSave->rol_mod_rep;
            $count = $count+1;
        }
        
        if(count($sendMails) > 0) {
            $notificacion->notificacionAsginacionConfig($sendMails);
        }

        return 'true';
    }

    public function searchEmployeeLabora(Request $request) {
        $sql = new ActivedirectoryEmployees;
        return $sql->getEmployeeByNumEmp($request->post('valor'));
    }
    public function anyData()
    {
        // $apps = new FusConfiguracionesAutorizaciones;
        // $data = $apps->data();
        $apps = new ListaResponsabilidades;
        $data = $apps->responsabilidades();
        return Datatables::of($data)->make(true);
    }
    public function baja(Request $request)
    {
        $auts = new FusConfiguracionesAutorizaciones;
        $val = $request->post('id');
        $con = new FusUserLogin;
        $idEmployee = $con->getIdByNameUser(Auth::user()->name);
        if($idEmployee == 0) {
            $idEmployee['id'] = null;
        }
        $auts->baja_logica($val, $idEmployee['id']);
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Baja de configuraciones de autorizaciones de operaciones',
            'tipo' => 'baja',
            'id_user' => $idEmployee['id']
        );
        
        $appsConfiguraciones = new FusConfiguracionesAutorizaciones;

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        echo true;
    }
    public function autocomplete(Request $request){
        $configuraciones = new FusConfiguracionesAutorizaciones;
        $var = $request->get('term');
        $term = (isset($var)) ? $var : 0;
        if ($term === 0) {
            return json_encode($result[] = array(
                'respuesta' => 'No se encontro el registro'
            ));
        } else {
            $result = $configuraciones->autocomplete($term, $request->post("idapp"));
            if (count($result) > 0 ) {
                return json_encode($result);
            }
            else {
                return json_encode($result[] = array('respuesta'=>'No se encontro el registro'));
            }
        }
    }

    public function totalbyresponsabilidad(Request $request) {
        $configuraciones = new FusConfiguracionesAutorizaciones;
        return $configuraciones->totalbyresponsabilidad($request->post("id"));
    }

    // si tipo es 1 es para saber si es MC, autorizador o ratificador
    // si tipo es 2 es para saber el tipo de objeto de autorización, ej. Responsabilidad
    public function getTitleAsignacion($tipo, $clave) { 
        switch ($tipo) {
            case 1:
                switch ($clave) {
                    case 1:
                        return "Mesa de Control";
                        break;
                    case 2:
                        return "Autorizador";
                        break;
                    case 3:
                        return "Ratificador";
                        break;
                }
                break;
            case 2:
                switch ($clave) {
                    case 0:
                        return "Aplicación";
                        break;
                    case 1:
                        return "Grupo";
                        break;
                    case 2:
                        return "Reporte";
                        break;
                    case 3:
                        return "Responsabilidad";
                        break;
                    case 4:
                        return "Rol";
                        break;
                    case 5:
                        return "Otros";
                        break;
                    case 6:
                        return "Perfiles";
                        break;
                    case 7:
                        return "Funciones";
                        break;
                    case 8:
                        return "Empresas";
                        break;
                    case 9:
                        return "Instancia";
                        break;
                    case 10:
                        return "Áreas";
                        break;
                    case 11:
                        return "Permisos";
                        break;
                    case 12:
                        return "Administración";
                        break;
                    case 13:
                        return "Portafolios";
                        break;
                    case 14:
                        return "Fondo";
                        break;
                    case 15:
                        return "Perfil SOS";
                        break;
                }
                break;
        }
    }
}