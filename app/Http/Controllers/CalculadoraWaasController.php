<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogBookMovements;
use App\Catalogosmodel;
use App\FusConfiguracionesAutorizaciones;
use App\RelFusApps;
use App\generalModel;
use App\RelConfigurationfussyswtl;
use App\FUSSysadminWtl;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CalculadoraWaasController extends Controller
{
    public $jerarquias;
    public $ip_address_client;
    protected $id_configuraciones = array();
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->jerarquias = config('app.jerarquias');
        $this->middleware('auth');
    }

    public function index() {
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de selección de aplicaciones para el FUS de aplicaciones.',
            'tipo' => 'vista',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
            // 'id_user' => 1
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view('fussysadmin.fusseleccionarapps');
    }

    public function formRequest(Request $request) {
        $old = (session('_old_input') != null) ? session('_old_input') : null;
        
            switch (count($request->post())) {
                case 0:
                case 1:
                    if ($old == null) {
                        return redirect()->route('seleccionfusapps');
                    }
                    break;
            }
        
        $data = new generalModel;
        $con2 = $data->db2();
        $result = $data->ejecutar_consulta($con2, Auth::user()->noEmployee, 1);
        $jer = $this->jerarquias;
        $jerar = base64_encode(json_encode($jer));
        $param['data'] = $result;
        $param['route'] = 'FusWintelController@insert_fus';
        $param['tipo_fus'] = 0;
        $param['jer'] = $jerar;
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de FUS de aplicaciones.',
            'tipo' => 'vista',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        $aplicaciones = array();
        if ($old == null) {
            foreach($request->post() AS $keys => $value) {
                $aplicaciones[$keys] = $this->appsTitulos($keys);
            }
        }else{
            foreach($old['aplicaciones'] AS $keys => $value) {
                $aplicaciones[$keys] = $this->appsTitulos($keys);
            }
        }
        unset($aplicaciones['_token']);
        return view('fussysadmin.fusaplicaciones')->with(['aplicaciones' => $aplicaciones, 'param' => $param, 'apps' => json_encode($aplicaciones)]);
    }

    public function store($request, $id_fus) {
        $post = $request;
        $json = (object)array();
        $txt = json_encode($post);
        Storage::disk('local')->put('cuerpo_fus_'.$id_fus.'.txt', $txt);
        foreach($post AS $keys => $value) {
            $jsonReponse = $this->toAssembleJSON($keys, $value);
            $relFusApp = new RelFusApps;
            switch ($value['altabajacambio_'.$keys]) {
                case 'a':
                    $tipoMovimiento = 1;
                    break;
                case 'b':
                    $tipoMovimiento = 3;
                    break;
                case 'c':
                    $tipoMovimiento = 2;                    
                    break;
                default:
                    # code...
                    break;
            }
            
            $relFusApp->relFusApp($id_fus, $keys, null, $tipoMovimiento);
            if($jsonReponse != null) {
                $json->{$keys} = $jsonReponse;
            }
        }

        $fus = FUSSysadminWtl::find($id_fus);
        $fus->tipo_fus = 0;
        $fus->fus_cuerpo = json_encode($json, JSON_PRETTY_PRINT);
        
        if($fus->save()) {
            foreach ($this->id_configuraciones as $key => $value) {
                $relConfigFus = new RelConfigurationfussyswtl;
                $relConfigFus->fus_sysadmin_wtl_id = $fus->id;
                $relConfigFus->fus_configuracion_autorizaciones_id  = $value;
                $relConfigFus->save();
            }

            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se genero fus de aplicaciones',
                'tipo' => 'alta',
                'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
            );

            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);
        }
    }

    public function getConfiguracionAutorizaciones(Request $request) {
        $configuracionesAutorizaciones = new FusConfiguracionesAutorizaciones;
        
        return response()->json($configuracionesAutorizaciones->getConfiguracionAutorizaciones($request->post('clave'), $request->post('tipo'), $request->post('idext'), $request->post('tipocatalogo')));
    }

    public function getCatalogoYOpciones(Request $request) {
        $catalogos = new Catalogosmodel;
        return response()->json($catalogos->getCatalogoYOpciones($request->post('catalogo'), $request->post('tipocatalogo'), $request->post('clave'), $request->post('idCatPrin'), $request->post('idAut'), $request->post('catalogo2')));
    }    
    
    public function getIdsConfigs($arrIds) {
        $configuracionesAutorizaciones = new FusConfiguracionesAutorizaciones;
        $idsQuerys = $configuracionesAutorizaciones->getIdsForFus($arrIds);

        if(!empty($idsQuerys)) {
            foreach($idsQuerys AS $keys => $value) {
                array_push($this->id_configuraciones, $value);
            }
        }
    }
    
    public function toAssembleJSON($cod, $objeto) {
        if($objeto['altabajacambio_'.$cod] != "b") {
            switch ($cod) {
                case 2:
                // case 'analyticsweb':
                    
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                            "tipo_solicitud" => array(
                                "etiqueta" => "Tipo de Solicitud",
                                "valor" => $objeto['altabajacambio_'.$cod]
                            ),
                            "rol" => array(
                                "etiqueta" => "Rol",
                                "valor" => $objeto['hiddenRol_'.$cod],
                                "tipo" => 1
                            )
                        );
                        
                    break;

                case 3:
                // case 'apexformulaciones':

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenAplicacion_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "aplicacion" => array(
                            "etiqueta" => "Aplicación",
                            "valor" => $objeto['hiddenAplicacion_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 4:
                // case 'apextv-networks':
                    
                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenAplicacion_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "aplicacion" => array(
                            "etiqueta" => "Aplicación",
                            "valor" => (isset($objeto['hiddenAplicacion_'.$cod])) ? $objeto['hiddenAplicacion_'.$cod] : '',
                            "tipo" => 1   
                        )
                    );

                    break;

                case 5:
                // case 'bookup':

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perfil = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "tipo_usuario" => array(
                            "etiqueta" => "Tipo Usuario",
                            "valor" => (isset($objeto['tipousuario_'.$cod])) ? $objeto['tipousuario_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "area" => array(
                            "etiqueta" => "Areas",
                            "valor" => (isset($objeto['hiddenAreas_'.$cod])) ? $objeto['hiddenAreas_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfiles",
                            "valor" => $perfil,
                            "tipo" => 1
                        )
                    );
                    
                    break;

                case 6:
                // case 'bookuptim':

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "tipo_usuario" => array(
                            "etiqueta" => "Tipo Usuario",
                            "valor" => (isset($objeto['tipousuario_'.$cod])) ? $objeto['tipousuario_'.$cod] : '',
                            "tipo" => 2
                        ),
                        // "no_dfp" => array(
                        //     "etiqueta" => "# Usuario DFP",
                        //     "valor" => $objeto['nodfp_'.$cod]
                        // ),
                        "usuario_dfp" => array(
                            "etiqueta" => "Usuario en DFP",
                            "valor" => (isset($objeto['usuariodfp_'.$cod])) ? $objeto['usuariodfp_'.$cod]  : '',
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfiles",
                            "valor" => $objeto['hiddenPerfil_'.$cod],
                            "tipo" => 1
                        )
                    );
                    
                    break;

                case 7:
                // case 'catalogoscorporativos':

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        ),
                        "tipo_acceso" => array(
                            "etiqueta" => "Tipo de Acceso",
                            "valor" => (isset($objeto['tipoacceso_'.$cod])) ? $objeto['tipoacceso_'.$cod]  : ''
                        )
                    );
                    break;

                case 9:
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $valemp = (isset($objeto['hiddenEmpresa_'.$cod])) ? $objeto['hiddenEmpresa_'.$cod]  : '';
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);
                    $empresa = str_replace("_", ",", $valemp);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        ),
                        "empresa" => array(
                            "etiqueta" => "Empresas",
                            "valor" => $empresa,
                            "tipo" => 2
                        ),
                        "modulo" => array(
                            "etiqueta" => "Módulos",
                            "valor" => (isset($objeto['modulo_'.$cod])) ? $objeto['modulo_'.$cod]  : '',
                            "tipo" => 2
                        )
                    );

                    break;

                case 10:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        )
                    );
                    
                    break;

                case 12:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenOtros_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $otros = str_replace("_", ",", $objeto['hiddenOtros_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "autorizador_unico" => array(
                            "etiqueta" => "",
                            "valor" => $otros,
                            "tipo" => 1
                        )
                        // "perfil" => array(
                        //     "etiqueta" => "Perfiles",
                        //     "valor" => $objeto['hiddenPerfil_'.$cod]
                        // ),
                        // "autor" => array(
                        //     "etiqueta" => "Autor",
                        //     "valor" => $objeto['autor_'.$cod]
                        // ),
                        // "consumidor" => array(
                        //     "etiqueta" => "Consumidor",
                        //     "valor" => $objeto['consumidor_'.$cod]
                        // )
                    );
                    break;

                case 13:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);
                    
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        ),
                        "unidad_negocio" => array(
                            "etiqueta" => "Unidad de Negocio",
                            "valor" => (isset($objeto['unidadnegocio_'.$cod])) ? $objeto['unidadnegocio_'.$cod]  : '',
                            "tipo" => 2
                        )
                    );

                    break;

                case 14:
                    
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        )
                    );

                    break;

                case 15:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 16:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenReporteProd_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenReporteIntermex_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    
                    $reporteprod = str_replace("_", ",", $objeto['hiddenReporteProd_'.$cod]);
                    $reporteintermex = str_replace("_", ",", $objeto['hiddenReporteIntermex_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        // "prod" => array(
                        //     "etiqueta" => "Prod",
                        //     "valor" => (isset($objeto['prod_'.$cod])) ? $objeto['prod_'.$cod] : ''
                        // ),
                        "reporte_prod" => array(
                            "etiqueta" => "Reporte Prod",
                            "valor" => $reporteprod,
                            "tipo" => 1
                        ),
                        // "intermex" => array(
                        //     "etiqueta" => "Intermex",
                        //     "valor" => (isset($objeto['intermex_'.$cod])) ? $objeto['intermex_'.$cod] : ''
                        // ),
                        "reporte_intermex" => array(
                            "etiqueta" => "Reporte Intermex",
                            "valor" => $reporteintermex,
                            "tipo" => 1
                        )
                    );

                    break;

                case 17:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenGrupo_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "repositorio" => array(
                            "etiqueta" => "Repositorio",
                            "valor" => (isset($objeto['hiddenRepositorio_'.$cod])) ? $objeto['hiddenRepositorio_'.$cod]  : '',
                            "tipo" => 2
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => (isset($objeto['hiddenGrupo_'.$cod])) ? $objeto['hiddenGrupo_'.$cod]  : '',
                            "tipo" => 2
                        )
                    );

                    break;

                case 19:

                    $idConfiguracionAutorizadores = explode(',', $objeto['administracion_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $emp = (isset($objeto['hiddenEmpresa_'.$cod])) ? $objeto['hiddenEmpresa_'.$cod]  : '';
                    $empresa = str_replace("_", ",", $emp);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "empresa_rfc" => array(
                            "etiqueta" => "Empresa y RFC",
                            "valor" => $empresa,
                            "tipo" => 2

                        ),
                        "serie" => array(
                            "etiqueta" => "Serie",
                            "valor" => (isset($objeto['serie_'.$cod])) ? $objeto['serie_'.$cod]  : ''
                        ),
                        "administracion" => array(
                            "etiqueta" => "Administracion",
                            "valor" => $objeto['administracion_'.$cod],
                            "tipo" => 1
                        ),
                        "requisicion" => array(
                            "etiqueta" => "No. Requisición",
                            "valor" => (isset($objeto['norequisicion_'.$cod])) ? $objeto['norequisicion_'.$cod]  : ''
                        ),
                        "tipo_usuario" => array(
                            "etiqueta" => "Tipo de usuario",
                            "valor" => (isset($objeto['tipousuario_'.$cod])) ? $objeto['tipousuario_'.$cod]  : '',
                            "tipo" => 2
                        )
                    );
                    
                    break;

                case 18:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenEmpresa_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $empresa = str_replace("_", ",", $objeto['hiddenEmpresa_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "empresa" => array(
                            "etiqueta" => "Empresa",
                            "valor" => $empresa,
                            "tipo" => 1
                        )
                    );

                    break;

                case 20:
                    $idCA = array();
                    
                    if($objeto['hiddenResponsabilidad_prod_'.$cod] != null){
                        $idConfiguracionAutorizadores = explode('_', $objeto['hiddenResponsabilidad_prod_'.$cod]);
                        foreach($idConfiguracionAutorizadores AS $index => $value) {
                            $temp = explode('-', $value);
                            array_push($idCA, $temp[1]);
                        }

                        $formateadaProd = str_replace("_", ",", $objeto['hiddenResponsabilidad_prod_'.$cod]);
                    } else {
                        $formateadaProd = '';
                    }

                    if($objeto['hiddenResponsabilidad_intermex_'.$cod] != null) {
                        $idConfiguracionAutorizadoresIntermex = explode('_', $objeto['hiddenResponsabilidad_intermex_'.$cod]);
                        foreach($idConfiguracionAutorizadoresIntermex AS $index => $value) {
                            $temp = explode('-', $value);
                            array_push($idCA, $temp[1]);
                        }
                        $formateadaIntermex = str_replace("_", ",", $objeto['hiddenResponsabilidad_intermex_'.$cod]);
                    }else {
                        $formateadaIntermex = '';
                    }

                    $this->getIdsConfigs($idCA);
                    

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        // "prod" => array(
                        //     "etiqueta" => "Prod",
                        //     "valor" => (isset($objeto['prod_'.$cod])) ? $objeto['prod_'.$cod] : ''
                        // ),
                        // "intermex" => array(
                        //     "etiqueta" => "Intermex",
                        //     "valor" => (isset($objeto['intermex_'.$cod])) ? $objeto['intermex_'.$cod] : ''
                        // ),
                        "responsabilidad_prod" => array(
                            "etiqueta" => "Responsabilidades Prod",
                            "valor" => $formateadaProd,
                            "tipo" => 1
                        ),
                        "responsabilidad_intermex" => array(
                            "etiqueta" => "Responsabilidades Intermex",
                            "valor" => $formateadaIntermex,
                            "tipo" => 1
                        )
                    );

                    break;
                // ERP CLOUD
                case 1032:
                    $idCA = array();
                    
                    if($objeto['hiddenResponsabilidad_prod_'.$cod] != null){
                        $idConfiguracionAutorizadores = explode('_', $objeto['hiddenResponsabilidad_prod_'.$cod]);
                        foreach($idConfiguracionAutorizadores AS $index => $value) {
                            $temp = explode('-', $value);
                            array_push($idCA, $temp[1]);
                        }

                        $formateadaProd = str_replace("_", ",", $objeto['hiddenResponsabilidad_prod_'.$cod]);
                    } else {
                        $formateadaProd = '';
                    }

                    // if($objeto['hiddenResponsabilidad_intermex_'.$cod] != null) {
                    //     $idConfiguracionAutorizadoresIntermex = explode('_', $objeto['hiddenResponsabilidad_intermex_'.$cod]);
                    //     foreach($idConfiguracionAutorizadoresIntermex AS $index => $value) {
                    //         $temp = explode('-', $value);
                    //         array_push($idCA, $temp[1]);
                    //     }
                    //     $formateadaIntermex = str_replace("_", ",", $objeto['hiddenResponsabilidad_intermex_'.$cod]);
                    // }else {
                    //     $formateadaIntermex = '';
                    // }

                    $this->getIdsConfigs($idCA);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "responsabilidad_prod" => array(
                            "etiqueta" => "Responsabilidades Prod",
                            "valor" => $formateadaProd,
                            "tipo" => 1
                        )
                        // "responsabilidad_intermex" => array(
                        //     "etiqueta" => "Responsabilidades Intermex",
                        //     "valor" => $formateadaIntermex,
                        //     "tipo" => 1
                        // )
                    );
                    break;
                // FIN ERP CLOUD
                case 65:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perfil = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfiles",
                            "valor" => $perfil,
                            "tipo" => 1
                        )
                    );

                    break;

                case 1027:
                    
                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenOtros_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "observaciones" => array(
                            "etiqueta" => "Observaciones",
                            "valor" => (isset($objeto['observaciones_'.$cod])) ? $objeto['observaciones_'.$cod]  : ''
                        ),
                        "autorizador_unico" => array(
                            "etiqueta" => "",
                            "valor" => $objeto['hiddenOtros_'.$cod]
                        )
                    );

                    break;
                case 1028:
                    
                        $idConfiguracionAutorizadores = explode('_', $objeto['perfil_'.$cod]);
                        $this->getIdsConfigs($idConfiguracionAutorizadores);
        
                        $jsonArray = array(
                            "tipo_solicitud" => array(
                                "etiqueta" => "Tipo de Solicitud",
                                "valor" => $objeto['altabajacambio_'.$cod]
                            ),
                            "portal" => array(
                                "etiqueta" => "Portal",
                                "valor" => (isset($objeto['portales_'.$cod])) ? $objeto['portales_'.$cod]  : '',
                                "tipo" => 2
                            ),
                            "perfil" => array(
                                "etiqueta" => "Perfil",
                                "valor" => $objeto['perfil_'.$cod],
                                "tipo" => 1
                            )
                        );
    
                        break;
                case 22:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        ),
                        "tipo" => array(
                            "etiqueta" => "Tipo",
                            "valor" => (isset($objeto['hiddenTipo_'.$cod])) ? $objeto['hiddenTipo_'.$cod] : ''
                        )
                    );

                    break;

                case 23:

                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenGrupo_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenAplicacion_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $gru = (isset($objeto['hiddenGrupo_'.$cod])) ? $objeto['hiddenGrupo_'.$cod] : '';
                    $grupo = str_replace("_", ",", $gru);
                    $aplicaciones = str_replace("_", ",", $objeto['hiddenAplicacion_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $grupo,
                            "tipo" => 2
                        ),
                        "aplicacion" => array(
                            "etiqueta" => "Aplicación",
                            "valor" => $aplicaciones,
                            "tipo" => 1
                        )
                    );

                    break;

                case 24:

                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenGrupo_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenAplicacion_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $gru = (isset($objeto['hiddenGrupo_'.$cod])) ? $objeto['hiddenGrupo_'.$cod] : '';
                    $grupo = str_replace("_", ",", $gru);
                    $aplicaciones = str_replace("_", ",", $objeto['hiddenAplicacion_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $grupo,
                            "tipo" => 2
                        ),
                        "aplicacion" => array(
                            "etiqueta" => "Aplicación",
                            "valor" => $aplicaciones,
                            "tipo" => 1
                        )
                    );

                    break;

                case 1024:

                    $idConfiguracionAutorizadores = explode(',', $objeto['aplicacion_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "labora" => array(
                            "etiqueta" => "Aplicativo",
                            "valor" => $objeto['aplicacion_'.$cod],
                            "tipo" => 1
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => (isset($objeto['hiddenPerfil_'.$cod])) ? $objeto['hiddenPerfil_'.$cod] : ''
                        )
                    );

                    break;

                case 27:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenInstancia_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $instancias = str_replace("_", ",", $objeto['hiddenInstancia_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "niveles_seguridad" => array(
                            "etiqueta" => "Niveles de Seguridad",
                            "valor" => (isset($objeto['nivelesdeseguridad_'.$cod])) ? $objeto['nivelesdeseguridad_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "departamento" => array(
                            "etiqueta" => "Departamento",
                            "valor" => (isset($objeto['departamento_'.$cod])) ? $objeto['departamento_'.$cod] : ''
                        ),
                        "localidades_pago" => array(
                            "etiqueta" => "Localidades de Pago",
                            "valor" => (isset($objeto['localidadesdepago_'.$cod])) ? $objeto['localidadesdepago_'.$cod] : '' 
                        ),
                        "menu" => array(
                            "etiqueta" => "Menú",
                            "valor" => (isset($objeto['menu_'.$cod])) ? $objeto['menu_'.$cod] : ''
                        ),
                        "conceptos" => array(
                            "etiqueta" => "Conceptos",
                            "valor" => (isset($objeto['conceptos_'.$cod])) ? $objeto['conceptos_'.$cod] : ''
                        ),
                        "procesos" => array(
                            "etiqueta" => "Procesos",
                            "valor" => (isset($objeto['procesos_'.$cod])) ? $objeto['procesos_'.$cod] : ''
                        ),
                        "instancias" => array(
                            "etiqueta" => "Instancias",
                            "valor" => $instancias,
                            "tipo" => 1
                        ),
                        "otros" => array(
                            "etiqueta" => "Otros",
                            "valor" => (isset($objeto['otrostxt_'.$cod])) ? $objeto['otrostxt_'.$cod] : ''
                        )    
                    );

                    break;

                case 1009:

                    $idConfiguracionAutorizadores = explode('_', (isset($objeto['perfiles_'.$cod])) ? $objeto['perfiles_'.$cod] :  $objeto['perfillandmarksales_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    // dd($objeto);
                    $sos = (isset($objeto['perfi_sos_'.$cod])) ? $objeto['perfi_sos_'.$cod] : 'off';
                    if ($sos == 'on') {
                        $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                            ),
                        "perfil_sos" => array(
                           "etiqueta" => "Perfil SOS",
                           "valor" => (isset($objeto['perfillandmarksales_'.$cod])) ? $objeto['perfillandmarksales_'.$cod] : null,
                           "tipo" => 1
                            ),
                        "motivo_sos" => array(
                            "etiqueta" => "Motivo de la asignación",
                            "valor" => (isset($objeto['motivoasignacion_'.$cod])) ? $objeto['motivoasignacion_'.$cod] : null
                            ),
                        "fecha_asignacion_sos" => array(
                            "etiqueta" => "Fecha de Asignación",
                            "valor" => (isset($objeto['fechaasignacion_'.$cod])) ? $objeto['fechaasignacion_'.$cod] : null
                            ),
                            "otros" => array(
                                "etiqueta" => "Otros",
                                "valor" => (isset($objeto['otrostxt_'.$cod])) ? $objeto['otrostxt_'.$cod] : ''
                            )
                        );
                    }else{
                        $jsonArray = array(
                            "tipo_solicitud" => array(
                                "etiqueta" => "Tipo de Solicitud",
                                "valor" => $objeto['altabajacambio_'.$cod]
                            ),
                            "perfil" => array(
                                "etiqueta" => "Perfil",
                                "valor" =>(isset($objeto['perfiles_'.$cod])) ? $objeto['perfiles_'.$cod] : null,
                                "tipo" => 1
                            ),
                            "id_red" => array(
                                "etiqueta" => "ID Red (Name)",
                                "valor" =>(isset($objeto['idred_'.$cod])) ? $objeto['idred_'.$cod] : null 
                            ),
                            "jefe_inmediato" => array(
                                "etiqueta" => "Nombre del Jefe Inmediato",
                                "valor" => (isset($objeto['nombrejefeinmediato_'.$cod])) ? $objeto['nombrejefeinmediato_'.$cod] : null
                            ),
                            "ubicacion_laboral" => array(
                                "etiqueta" => "Ubicación Laboral",
                                "valor" =>(isset($objeto['ubicacionlaboral_'.$cod])) ? $objeto['ubicacionlaboral_'.$cod] : null,
                                "tipo" => 2
                            ),
                            "otros" => array(
                                "etiqueta" => "Otros",
                                "valor" => (isset($objeto['otrostxt_'.$cod])) ? $objeto['otrostxt_'.$cod] : ''
                            )
                        );
                    }
                    break;

                case 31:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);
                    $grupoeditorial = str_replace("_", ",", $objeto['hiddenGrupoeditorial_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo_editorial" => array(
                            "etiqueta" => "Grupo Editorial",
                            "valor" => $grupoeditorial,
                            "tipo" => 2
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $objeto['hiddenPerfil_'.$cod],
                            "tipo" => 1
                        )
                        ,
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 2
                        )
                    );

                    break;

                case 32:
                
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perfil = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);
                    $empresa = str_replace("_", ",", $objeto['hiddenEmpresa_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $perfil,
                            "tipo" => 1
                        ),
                        "empresa" => array(
                            "etiqueta" => "Empresas",
                            "valor" => $empresa,
                            "tipo" => 2
                        )
                    );
                    

                    break;

                case 33:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenOtros_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $grupo = str_replace("_", ",", $objeto['hiddenGrupo_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $grupo,
                            "tipo" => 2
                        ),

                        "area_insercion" => array(
                            "etiqueta" => "Área de Inserción",
                            "valor" => (isset($objeto['areasInsercion_'.$cod])) ? $objeto['areasInsercion_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "estado" => array(
                            "etiqueta" => "Estado",
                            "valor" => (isset($objeto['estado_'.$cod])) ? $objeto['estado_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "tipo_empleado" => array(
                            "etiqueta" => "Tipo Empleado",
                            "valor" => (isset($objeto['tipoEmpleado_'.$cod])) ? $objeto['tipoEmpleado_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "otros" => array(
                            "etiqueta" => "Otro",
                            "valor" => (isset($objeto['otrotxt_'.$cod])) ? $objeto['otrotxt_'.$cod] : ''
                        )
                    );

                    break;

                // case 'maximovideocine':
                //     break;

                case 1001:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        )
                        // ,
                        // "modulo" => array(
                        //     "etiqueta" => "Módulo(s)",
                        //     "valor" => $objeto['modulos_'.$cod]
                        // ),
                        // "instancia" => array(
                        //     "etiqueta" => "Instancias",
                        //     "valor" => $objeto['instancias_'.$cod]
                        // )
                    );
                    break;

                case 35:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perfil = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $perfil,
                            "tipo" => 1
                        )
                    );

                    break;

                case 1003:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenGrupo_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $grupo = str_replace("_", ",", $objeto['hiddenGrupo_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $grupo,
                            "tipo" => 1
                        )
                    );

                    break;

                case 8:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;
                case 37:
                    $idConfiguracionAutorizadores[] = (isset($objeto['areasforparadigm_'.$cod])) ? $objeto['areasforparadigm_'.$cod] : $objeto['hiddenAreasForParadigm_'.$cod];
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $canales = (isset($objeto['hiddenCanalesparadigm_'.$cod])) ? str_replace("_", ",",$objeto['hiddenCanalesparadigm_'.$cod]) : null;
                    
                    $jsonArray = array(
                        'tipo_solicitud' => array(
                            'etiqueta' => "Tipo de Solicitud",
                            'valor' => $objeto['altabajacambio_'.$cod]
                        ),
                        'usuario_referenciaparadigm' => array(
                            'etiqueta' => "Usuario de referencia",
                            'valor' => (isset($objeto['usuarioreferenciaparadigm_'.$cod])) ? $objeto['usuarioreferenciaparadigm_'.$cod] : null
                        ),
                        'canales_paradigm'=> array(
                            'etiqueta' => "Canales",
                            'valor' => $canales,
                            'tipo' => 2
                        ),
                        'areas_paradigm' => array(
                            'etiqueta' => "Áreas",
                            'valor' => (isset($objeto['areasforparadigm_'.$cod])) ? $objeto['areasforparadigm_'.$cod] : $objeto['hiddenAreasForParadigm_'.$cod],
                            'tipo' => 1
                        ),
                        "otros" => array(
                            "etiqueta" => "Otros: ",
                            "valor" => (isset($objeto['otrostxt_'.$cod])) ? $objeto['otrostxt_'.$cod] : ''
                        )
                    );
                    break;                    
                case 39:
                    $equi = (isset($objeto['hiddenEquipo_'.$cod])) ? $objeto['hiddenEquipo_'.$cod] : '';
                    $equipo = str_replace("_", ",", $equi);
                    $tcaso = (isset($objeto['hiddenTipocaso_'.$cod])) ? $objeto['hiddenTipocaso_'.$cod] : '';
                    $tipocaso = str_replace("_", ",", $tcaso);
                    $rol_ = (isset($objeto['hiddenRol_'.$cod])) ? $objeto['hiddenRol_'.$cod] : '';
                    $rol = str_replace("_", ",", $rol_);

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        ),
                        "tipo_caso" => array(
                            "etiqueta" => "Tipo de Caso",
                            "valor" => $tipocaso,
                            "tipo" => 2
                        ),
                        "equipo" => array(
                            "etiqueta" => "Equipo",
                            "valor" => $equipo,
                            "tipo" => 2
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $objeto['grupo_'.$cod],
                            "tipo" => 2
                        )
                    );

                    break;

                case 38:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 40:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 1004:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $not = (isset($objeto['hiddenNotificacion_'.$cod])) ? $objeto['hiddenNotificacion_'.$cod] : '';
                    $notificacion = str_replace("_", ",", $not);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $objeto['hiddenPerfil_'.$cod],
                            "tipo" => 1
                        ),
                        "notificacion" => array(
                            "etiqueta" => "Notificaciones por correo",
                            "valor" => $notificacion,
                            "tipo" => 2
                        )
                    );

                    break;

                case 41:

                    $idConfiguracionAutorizadores = explode(',', $objeto['perfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "sala" => array(
                            "etiqueta" => "Sala",
                            "valor" => (isset($objeto['sala_'.$cod])) ? $objeto['sala_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $objeto['perfil_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 1007:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenGrupo_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $grupos = str_replace("_", ",", $objeto['hiddenGrupo_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $grupos,
                            "tipo" => 1
                        )
                    );

                    break;

                case 44:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 43:
                
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPermisos_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $permisos = str_replace("_", ",", $objeto['hiddenPermisos_'.$cod]);
                    $Portafolios = str_replace("_", ",", $objeto['hiddenPortafolios_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "tipo_permiso" => array(
                            "etiqueta" => "Tipo de Permisos",
                            "valor" => $permisos,
                            "tipo" => 1
                        ),
                        "permiso_portafolio" => array(
                            "etiqueta" => "Portafolios",
                            "valor" => $Portafolios,
                            "tipo" => 2
                        )
                    );

                    break;

                case 45:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 46:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPermisos_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $permisos = str_replace("_", ",", $objeto['hiddenPermisos_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "permiso" => array(
                            "etiqueta" => "Permisos",
                            // "valor" => $objeto['hiddenPermisos_'.$cod],
                            "valor" => $permisos,
                            "tipo" => 1
                        ),
                        "dominio" => array(
                            "etiqueta" => "DOMINIO",
                            "valor" => (isset($objeto['dominio_'.$cod])) ? $objeto['dominio_'.$cod] : ''
                        ),
                        "suario" => array(
                            "etiqueta" => "USUARIO",
                            "valor" => (isset($objeto['usuario_'.$cod])) ? $objeto['usuario_'.$cod] : ''
                        )
                    );

                    break;

                case 1005:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $empresa = str_replace("_", ",", $objeto['hiddenEmpresa_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        ),
                        "rfc_empresa" => array(
                            "etiqueta" => "Empresa(s)",
                            "valor" => $empresa,
                            "tipo" => 2
                        )
                    );
                    
                    break;

                case 64:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenReporteProd_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenReporteIntermex_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $reporteprod = str_replace("_", ",", $objeto['hiddenReporteProd_'.$cod]);
                    $reporteintermex = str_replace("_", ",", $objeto['hiddenReporteIntermex_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        // "prod" => array(
                        //     "etiqueta" => "Prod",
                        //     "valor" => (isset($objeto['prod_'.$cod])) ? $objeto['prod_'.$cod] : ''
                        // ),
                        "reporte_prod" => array(
                            "etiqueta" => "Reporte Prod",
                            "valor" => $reporteprod,
                            "tipo" =>1
                        ),
                        // "intermex" => array(
                        //     "etiqueta" => "Intermex",
                        //     "valor" => (isset($objeto['intermex_'.$cod])) ? $objeto['intermex_'.$cod] : ''
                        // ),
                        "reporte_intermex" => array(
                            "etiqueta" => "Reporte Intermex",
                            "valor" => $reporteintermex,
                            "tipo" => 1
                        )
                    );

                    break;

                case 69:

                    $idConfiguracionAutorizadores = explode(',', $objeto['fondo_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "fondo" => array(
                            "etiqueta" => "Fondo",
                            "valor" => (isset($objeto['fondo_'.$cod])) ? $objeto['fondo_'.$cod] : '',
                            "tipo" => 1
                        ),
                        "cajaahorros" => array(
                            "etiqueta" => "Caja de ahorro",
                            "valor" => (isset($objeto['cajaahorros_'.$cod])) ? $objeto['cajaahorros_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "tipousuario" => array(
                            "etiqueta" => "Tipo de usuario",
                            "valor" => (isset($objeto['tipousuario_'.$cod])) ? $objeto['tipousuario_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "perfilCentral" => array(
                            "etiqueta" => "Especificar perfil central",
                            "valor" => (isset($objeto['perfilCentral_'.$cod])) ? $objeto['perfilCentral_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "perfilInternet" => array(
                            "etiqueta" => "Especificar perfil internet",
                            "valor" => (isset($objeto['perfilInternet_'.$cod])) ? $objeto['perfilInternet_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "usuarioWeb" => array(
                            "etiqueta" => "Tipo de usuario web",
                            "valor" => (isset($objeto['usuarioWeb_'.$cod])) ? $objeto['usuarioWeb_'.$cod] : '',
                            "tipo" => 2
                        )
                        // "autorizador_unico" => array(
                        //     "etiqueta" => "",
                        //     "valor" => $objeto['hiddenOtros_'.$cod]
                        // )
                    );

                    break;

                case 47:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenFuncion_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $objeto['hiddenPerfil_'.$cod],
                            "tipo" => 1
                        ),
                        "funcion" => array(
                            "etiqueta" => "Función",
                            "valor" => (isset($objeto['hiddenFuncion_'.$cod])) ? $objeto['hiddenFuncion_'.$cod] : ''
                        )
                    );

                    break;

                case 62:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perfiles = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);
                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenFuncion_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $perfiles,
                            "tipo" => 1
                        ),
                        "infoadicional" => array(
                            "etiqueta" => "Info. Adicional",
                            "valor" => (isset($objeto['infoadicional_'.$cod])) ? $objeto['infoadicional_'.$cod] : ''
                        )
                    );

                    break;

                case 48:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        )
                    );

                    break;

                case 49:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 50:
                    if($objeto['altabajacambio_'.$cod] == "c" && empty($objeto['perfil_'.$cod])) {
                       $FusConfiguracionesAutorizaciones = new FusConfiguracionesAutorizaciones;
                       $idConfiguracionAutorizadores = $FusConfiguracionesAutorizaciones->getOtros($cod); 
                    } else {
                        $idConfiguracionAutorizadores = explode('_', $objeto['perfil_'.$cod]);
                    }
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $emp = (isset($objeto['hiddenEmpresa_'.$cod])) ? $objeto['hiddenEmpresa_'.$cod] : '';
                    $empresas = str_replace("_", ",", $emp);
                    $facu = (isset($objeto['hiddenFacultades_'.$cod])) ? $objeto['hiddenFacultades_'.$cod] : '';
                    $facultades = str_replace("_", ",", $facu);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $objeto['perfil_'.$cod],
                            "tipo" => 1
                        ),
                        "empresa" => array(
                            "etiqueta" => "Empresas",
                            "valor" => $empresas,
                            "tipo" => 2
                        ),
                        "extern_login" => array(
                            "etiqueta" => "Información Adicional",
                            "valor" => (isset($objeto['externlogin_'.$cod])) ? $objeto['externlogin_'.$cod] : ''
                        ),
                        "falcultad" => array(
                            "etiqueta" => "Facultades",
                            "valor" => $facultades,
                            "tipo" => 2
                        )
                    );

                    break;

                case 51:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenGrupo_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenAplicacion_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $objeto['hiddenGrupo_'.$cod],
                            "tipo" => 1
                        ),
                        "aplicacion" => array(
                            "etiqueta" => "Aplicación",
                            "valor" => (isset($objeto['hiddenAplicacion_'.$cod])) ? $objeto['hiddenAplicacion_'.$cod] : ''
                        )
                    );

                    break;

                case 76:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenAplicacion_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);
                    
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        ),
                        "aplicacion" => array(
                            "etiqueta" => "Aplicación",
                            "valor" => (isset($objeto['hiddenAplicacion_'.$cod])) ? $objeto['hiddenAplicacion_'.$cod] : ''
                        )
                    );

                    break;

                case 52:

                    // $idConfiguracionAutorizadores = explode(',', $objeto['hiddenOtros_'.$cod]);
                    // $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenPermisos_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "permiso" => array(
                            "etiqueta" => "Permiso",
                            "valor" => $objeto['hiddenPermisos_'.$cod],
                            "tipo" => 1
                        ),
                        "dominio_usuario" => array(
                            "etiqueta" => "Datos adicionales",
                            "valor" => (isset($objeto['dominiousuario_'.$cod])) ? $objeto['dominiousuario_'.$cod] : ''
                        )
                        // ,"autorizador_unico" => array(
                        //     "etiqueta" => "",
                        //     "valor" => $objeto['hiddenOtros_'.$cod]
                        // )
                    );

                    break;

                case 53:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenGrupo_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $grupos = str_replace("_", ",", $objeto['hiddenGrupo_'.$cod]);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $grupos,
                            "tipo" => 1
                        )
                    );

                    break;

                case 54:
                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $emp = (isset($objeto['hiddenEmpresa_'.$cod])) ? $objeto['hiddenEmpresa_'.$cod] : '';
                    $empresas = str_replace("_", ",", $emp);
                    $sub = (isset($objeto['hiddenSubsistemas_'.$cod])) ? $objeto['hiddenSubsistemas_'.$cod] : '';
                    $subsistemas = str_replace("_", ",", $sub);
                    $perfiles = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);
                    
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "sistema" => array(
                            "etiqueta" => "Sistema (Módulo)",
                            "valor" => (isset($objeto['sistema_'.$cod])) ? $objeto['sistema_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfiles",
                            "valor" => $perfiles,
                            "tipo" => 1
                        ),
                        "empresa" => array(
                            "etiqueta" => "Empresas",
                            "valor" => $empresas,
                            "tipo" => 2
                        ),
                        "sub_sistema" => array(
                            "etiqueta" => "Sub-Sistema",
                            "valor" => $subsistemas,
                            "tipo" => 2
                        )
                        // ,
                        // "autorizador_unico" => array(
                        //     "etiqueta" => "",
                        //     "valor" => $objeto['hiddenOtros_'.$cod],
                        //     "tipo" => 2
                        // )
                    );

                    break;

                case 55:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenOtros_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "niveles_seguridad" => array(
                            "etiqueta" => "Nivel de Seguridad",
                            "valor" => (isset($objeto['nivelesseguridad_'.$cod])) ? $objeto['nivelesseguridad_'.$cod] : ''
                            // "tipo" => 2
                        ),
                        "nomina" => array(
                            "etiqueta" => "Nómina",
                            "valor" => (isset($objeto['nomina_'.$cod])) ? $objeto['nomina_'.$cod] : ''
                        ),
                        "menu" => array(
                            "etiqueta" => "Menú",
                            "valor" => (isset($objeto['menu_'.$cod])) ? $objeto['menu_'.$cod] : ''
                        ),
                        "centro_costo" => array(
                            "etiqueta" => "Centro de Costos",
                            "valor" => (isset($objeto['centrodecostos_'.$cod])) ? $objeto['centrodecostos_'.$cod] : ''
                        ),
                        "area" => array(
                            "etiqueta" => "Áreas",
                            "valor" => (isset($objeto['areas_'.$cod])) ? $objeto['areas_'.$cod] : ''
                        ),
                        "procesos" => array(
                            "etiqueta" => "Procesos",
                            "valor" => (isset($objeto['procesos_'.$cod])) ? $objeto['procesos_'.$cod] : ''
                        ),
                        "actividades" => array(
                            "etiqueta" => "Actividades",
                            "valor" => (isset($objeto['actividades_'.$cod])) ? $objeto['actividades_'.$cod] : ''
                        ),
                        "otro" => array(
                            "etiqueta" => "Otros",
                            "valor" => (isset($objeto['optotros_'.$cod])) ? $objeto['optotros_'.$cod] : ''
                        ),
                        "autorizador_unico" => array(
                            "etiqueta" => "",
                            "valor" => $objeto['hiddenOtros_'.$cod]
                        )
                    );

                    break;

                case 56:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPermisos_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $permisos = str_replace("_", ",", $objeto['hiddenPermisos_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "Permiso" => array(
                            "etiqueta" => "Permisos",
                            "valor" => $permisos,
                            "tipo" => 1
                        ),
                        //  "permiso" => array(
                        //     "etiqueta" => "Permisos",
                        //     "valor" => $objeto['hiddenPermisos_'.$cod]
                        // ),
                        // "otro_permiso" => array(
                        //     "etiqueta" => "Datos adicionales",
                        //     "valor" => $objeto['OtroPermiso_'.$cod]
                        // )
                    );

                    break;

                case 11:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perfil = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfiles",
                            "valor" => $perfil,
                            "tipo" => 1
                        )
                    );
                    
                    break;

                case 57:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );
                    
                    break;
                    
                case 1011:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenPerfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perfil = str_replace("_", ",", $objeto['hiddenPerfil_'.$cod]);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfiles",
                            "valor" => $perfil,
                            "tipo" => 1
                        )
                    );

                    break;

                case 59:

                    $idConfiguracionAutorizadores = explode(',', $objeto['perfil_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => $objeto['perfil_'.$cod],
                            "tipo" => 1
                        ),
                        "salas" => array(
                            "etiqueta" => "Sala",
                            "valor" => (isset($objeto['salas_'.$cod])) ? $objeto['salas_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "otro" => array(
                            "etiqueta" => "Otro",
                            "valor" => (isset($objeto['otro_'.$cod])) ? $objeto['otro_'.$cod] : ''
                        )
                    );

                    break;

                case 63:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 1006:

                    $idConfiguracionAutorizadores = explode(',', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $objeto['hiddenRol_'.$cod],
                            "tipo" => 1
                        )
                    );

                    break;

                case 58:

                    $idConfiguracionAutorizadores = explode('_', $objeto['empresa_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $perf = (isset($objeto['hiddenPerfil_'.$cod])) ? $objeto['hiddenPerfil_'.$cod] : '';
                    $perfiles = str_replace("_", ",", $perf);
                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfiles",
                            "valor" => $perfiles,
                            "tipo" => 2
                        ),
                        "empresa" => array(
                            "etiqueta" => "Empresa",
                            "valor" => $objeto['empresa_'.$cod],
                            "tipo" => 1
                        ),
                        "tipousuario" => array(
                            "etiqueta" => "Tipo de Usuario",
                            "valor" => (isset($objeto['tipousuario_'.$cod])) ? $objeto['tipousuario_'.$cod] : '',
                            "tipo" =>2
                        )
                    );

                    break;

                case 66:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenGrupo_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "grupo" => array(
                            "etiqueta" => "Grupo",
                            "valor" => $objeto['hiddenGrupo_'.$cod],
                            "tipo" => 1
                        ),
                        "dominio" => array(
                            "etiqueta" => "Dominio",
                            "valor" => (isset($objeto['dominio_'.$cod])) ? $objeto['dominio_'.$cod] : ''
                            // ,"tipo" => 2
                        ),
                        "usuario" => array(
                            "etiqueta" => "Usuario",
                            "valor" => (isset($objeto['usuario_'.$cod])) ? $objeto['usuario_'.$cod] : ''
                        )
                    );

                    break;

                case 60:

                    $idConfiguracionAutorizadores = explode('_', $objeto['hiddenRol_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);
                    $rol = str_replace("_", ",", $objeto['hiddenRol_'.$cod]);
                    $emi = (isset($objeto['hiddenEmisora_'.$cod])) ? $objeto['hiddenEmisora_'.$cod] : '';
                    $emisora = str_replace("_", ",", $emi);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "rol" => array(
                            "etiqueta" => "Rol",
                            "valor" => $rol,
                            "tipo" => 1
                        ),
                        "emisora" => array(
                            "etiqueta" => "Emisora",
                            "valor" => $emisora,
                            "tipo" => 2
                        )
                    );
                    
                    break;

                case 61:

                    $idConfiguracionAutorizadores = explode(',', $objeto['lugar_'.$cod]);
                    $this->getIdsConfigs($idConfiguracionAutorizadores);

                    $jsonArray = array(
                        "tipo_solicitud" => array(
                            "etiqueta" => "Tipo de Solicitud",
                            "valor" => $objeto['altabajacambio_'.$cod]
                        ),
                        "ubicacion" => array(
                            "etiqueta" => "Ubicación",
                            "valor" => $objeto['lugar_'.$cod],
                            "tipo" => 1
                        ),
                        "perfil" => array(
                            "etiqueta" => "Perfil",
                            "valor" => (isset($objeto['perfil_'.$cod])) ? $objeto['perfil_'.$cod] : '',
                            "tipo" => 2
                        ),
                        "otros" => array(
                            "etiqueta" => "Otros",
                            "valor" => (isset($objeto['otrostxt_'.$cod])) ? $objeto['otrostxt_'.$cod] : ''
                        )
                    );

                    break;
            }

            if(isset($objeto['accion_'.$cod])) {
                $jsonArray["accion"] = array(
                    "etiqueta" => "Acción",
                    "valor" => $objeto['accion_'.$cod]
                );
            }

            return $jsonArray;
        } else {
            return array(
                "tipo_solicitud" => array(
                    "etiqueta" => "Tipo de Solicitud",
                    "valor" => $objeto['altabajacambio_'.$cod]
                )
            );
        }
    }

    public function appsTitulos($cod) {
        switch ($cod) {
            case '1028':
                return 'ACERVO Y OBRAS LITERARIAS';
                break;
            case '2':
                return 'ANALYTICS WEB';
                break;

            case '3':
                return 'APEX FORMULACIONES';
                break;

            case '4':
                return 'APEX TV-NETWORKS';
                break;

            case '5':
                return 'BOOKUP';
                break;

            case '6':
                return 'BOOKUP! TIM';
                break;

            case '7':
                return 'CATALOGOS CORPORATIVOS';
                break;

            case '9':
                return 'COFIDI';
                break;

            case '10':
                return 'CONCOM-SECMAN';
                break;

            case '12':
                return 'CREDITO CORP.';
                break;

            case '13':
                return 'CRM DYNAMICS PROTELE';
                break;

            case '14':
                return 'CRM TELEFONIA';
                break;

            case '15':
                return 'DAM';
                break;

            case '16':
                return 'DISCOVERER';
                break;

            case '17':
                return 'DOCUMENTUM';
                break;

            case '19':
                return 'EDIWIN-PLANTA Y HONORARIOS';
                break;

            case '18':
                return 'EDIWIN-EMISION';
                break;

            case '20':
                return 'ERP';
                break;
            
            case '1032':
                return 'ERP CLOUD';
                break;

            case '65':
                return 'EVE TV';
                break;

            case '1027':
                return 'FLUJO DE EFECTIVO CORP';
                break;

            case '22':
                return 'FLUJO DE EFECTIVO CxC "SOIN"';
                break;

            case '23':
                return 'HYPERION PLANNING';
                break;

            case '24':
                return 'HYPERION TV-NETWORKS';
                break;

            case '1024':
                return 'IMPROMPTU';
                break;

            case '27':
                return 'LABORA';
                break;

            case '1009':
                return 'LANDMARK SALES';
                break;

            case '31':
                return 'LIVE UP';
                break;

            case '32':
                return 'MAF';
                break;

            case '33':
                return 'MAXIMO VESTUARIO';
                break;

            case 'maximovideocine':
                return 'MAXIMO VIDEOCINE';
                break;

            case '1001':
                return 'ORACLE AACG';
                break;

            case '35':
                return 'ORDUNI-SECMAN';
                break;

            case '1003':
                return 'ORDWEB';
                break;

            case '8':
                return 'OPERACIONES CON VALOR';
                break;

            case '37':
                return 'PARADIGM';
                break;

            case '39':
                return 'PATRICIA';
                break;

            case '38':
                return 'PARRILLAS';
                break;

            case '40':
                return 'PENDIUM';
                break;

            case '1004':
                return 'PCB';
                break;

            case '41':
                return 'PIXEL POINT';
                break;

            case '1007':
                return 'PLAN COMERCIAL';
                break;

            case '44':
                return 'PORTAL DE PRODUCCION';
                break;

            case '43':
                return 'PORTAFOLIOS NET';
                break;

            case '45':
                return 'PRODUCTIVIDAD EN LINEA';
                break;

            case '46':
                return 'QLIKVIEW';
                break;

            case '1005':
                return 'RECIBOS DE NOMINA';
                break;

            case '64':
                return 'RENTABILIDAD DISCOVERER';
                break;

            case '69':
                return 'SAF';
                break;

            case '47':
                return 'SALESFORCE EDITORIAL';
                break;

            case '62':
                return 'SALESFORCE VENTAS';
                break;

            case '48':
                return 'SECMAN';
                break;

            case '49':
                return 'SECMAN 12';
                break;

            case '50':
                return 'SET';
                break;

            case '51':
                return 'SIASA';
                break;

            case '76':
                return 'SIASA 2';
                break;

            case '52':
                return 'SICEA';
                break;

            case '53':
                return 'SIFIC';
                break;

            case '54':
                return 'SIFIT';
                break;

            case '55':
                return 'SIHO';
                break;

            case '56':
                return 'SIM';
                break;

            case '11':
                return 'SIMM';
                break;

            case '57':
                return 'SISTEMAS DE GESTION RRHH';
                break;
                
            case '1011':
                return 'SMARTCONCIL';
                break;

            case '59':
                return 'SUC (WIGOS)';
                break;

            case '63':
                return 'TAXIS';
                break;

            case '1006':
                return 'TIEMPOS EFECTIVOS';
                break;

            case '58':
                return 'TVSPOT';
                break;

            case '66':
                return 'VIMBIZ';
                break;

            case '60':
                return 'XBRL';
                break;

            case '61':
                return 'XYTECH';
                break;
        }
    }

    public function atencionApps() {
        
    }
}
