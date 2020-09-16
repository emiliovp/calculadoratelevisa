<?php
namespace App\Http\Controllers;

use App\ActivedirectoryEmployees;
use App\Comparelaboraconcilia;
use App\ActiveDirectoriActive;
use App\RelFusCapturistaModel;
use App\FusCapturistaModel;
use App\LogBookMovements;
use App\Catalogosmodel;
use App\EmpresaFilial;
use App\FUSSysadminWtl;
use App\FusUserLogin;
use App\Op_cat_model;
use App\generalModel;
use App\RelAnexosFus;
use App\Http\Controllers;
use App\Http\Controllers\FusSysadminController;
use App\Http\Controllers\NotificacionesController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class FusWintelController extends Controller
{
    protected $requestdat;
    protected $app;
    /**
     *  Create a new controller instance.
     * 
     * @return void
     */
    public $jerarquias;
    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->jerarquias = config('app.jerarquias');
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function correo_usuario($tipo)
    {
        $data = new generalModel;

        $con2 = $data->db2();
        $result = $data->ejecutar_consulta($con2, Auth::user()->noEmployee, 1);
        $jer = $this->jerarquias;
        $jerar = base64_encode(json_encode($jer));
        $param['jer'] = $jerar;
        $param['data'] = $result;
        $param['data2'] = null;
        switch ($tipo) {
            case 1:
                $select = new Catalogosmodel;
                $query = $select->recuperar_opciones('DOMINIOS');
                $view = "fus_wintel.fusView";
                $param['route'] = "FusWintelController@insert_fus";
                $param['dominio'] = $query;
                $param['tipo_fus'] = 1;
                $param['tipo'] = 1;
                $param['data'] = null;
                $param['data2'] = $result;
                break;
            case 2:
                $select = new Catalogosmodel;
                $query = $select->recuperar_opciones('DOMINIOS');
                $SMTP = $select->recuperar_opciones('SMTP');
                $param['dominio'] = $query;
                $param['smtp'] = $SMTP;
                $view = "fus_wintel.fus_correoView";
                $param['route'] = "FusWintelController@insert_fus";
                $param['tipo_fus'] = 2;
                $param['tipo'] = 2;
                $param['data'] = null;
                $param['data2'] = $result;
                break;
            case 3:
                $select = new Catalogosmodel;
                $query = $select->recuperar_opciones('DOMINIOS');
                $SMTP = $select->recuperar_opciones('SMTP');
                $param['dominio'] = $query;
                $param['smtp'] = $SMTP;
                $view = "fus_wintel.fus_correo_especialView";
                $param['route'] = "FusWintelController@insert_fus";
                $param['tipo_fus'] = 3;
                break;
            case 4:
                $view = "fus_wintel.fus_acceso_carpeta_directorioView";
                $param['route'] = "FusWintelController@insert_fus";
                $param['tipo_fus'] = 4;
                break;
            case 5:
                    $view = "fus_wintel.fus_vpnView";
                    $param['route'] = "FusWintelController@insert_fus";
                    $param['tipo_fus'] = 5;
                    break;
            case 6:
                    $view = "fus_wintel.fus_autorizacion_de_acceso_a_la_redView";
                    $param['route'] = "FusWintelController@insert_fus";
                    $param['tipo_fus'] = 6;
                    break;
            default:
                # code...
                break;
        }
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de alta de fus',
            'tipo' => 'vista',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );
        // dd($param);
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        return view($view)->with("param", $param);
    }
    public function insert_fus(Request $request)
    {
        $t_fus = $request->post('tipo_fus');
        $this->requestdat = $request->post();
        $aplicaciones = $request->post('aplicaciones');
        $this->app = $aplicaciones;
        switch ($t_fus) {
            case '0':
                foreach ($this->app as $key => $value) {
                    $especial = null;
                    if ($key == 50) {
                        $especial = true;
                    }
                }
                if (!$especial) {
                    if($this->requestdat['d_ext'] == 1){
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => ['required',function($attribute, $puesto_jefe, $fail){
                                $jer = $this->jerarquias;
                                $t_persona = $this->requestdat['d_ext'];
                                if ($t_persona == 1) {
                                    $quit =  [ "Gte", "Gte.","Gerente", "GTE", "GTE.", "GERENTE"];
                                    $jer = array_diff($jer, $quit);
                                }else{
                                    $jer = array();
                                }
                                $termino = explode(" ", $puesto_jefe);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia minima solicitada (Coordinador)');
                                }
                            }],
                        ]);
                    }else{
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => 'required',
                            'n_aut' =>'required',
                            'correo_aut' => 'required',
                            'puesto_aut' => ['required',function($attribute, $puesto_aut, $fail){
                                $jer = $this->jerarquias;
                                $especial = null;
                                $t_persona = $this->requestdat['d_ext'];
                                $quit =  [ "Gte", "Gte.","Gerente", "GTE", "GTE.", "GERENTE"];
                                $jer = array_diff($jer, $quit);
                                $termino = explode(" ", $puesto_aut);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia minima solicitada (Coordinador)');
                                }
                            }],
                            'ficha_t' => 'required',
                            'empresa_t' => 'required',
                            'nombre_t' => 'required'
                        ]);
                    }
                }else{
                    if($this->requestdat['d_ext'] == 1){
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => ['required'],
                            'n_aut' =>'required|integer',
                            'correo_aut' => 'required',
                            'puesto_aut' => ['required',function($attribute, $puesto_aut, $fail){
                                $jer = $this->jerarquias;
                                $especial = null;
                                $t_persona = $this->requestdat['d_ext'];
                                $quit =  [ "Gte", "Gte.","Gerente", "GTE", "GTE.", "GERENTE", "Coord", "Coord.","COORD","COORD.","Coordinador","COORDINADOR","Coordinadora", "COORDINADORA","Subdir", "Subdir.","SUBDIR","SUBDIR.","SUBDIRECTOR", "SUBDIRECTORA","Sub-Director","SUB-DIRECTOR", "Sub-Directora","SUB-DIRECTORA"];
                                $jer = array_diff($jer, $quit);
                                $termino = explode(" ", $puesto_aut);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia minima de Director');
                                }
                            }]
                        ]);
                    }else{
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => ['required'],
                            'n_aut' =>'required|integer',
                            'correo_aut' => 'required',
                            'puesto_aut' => ['required',function($attribute, $puesto_aut, $fail){
                                $jer = $this->jerarquias;
                                $especial = null;
                                $t_persona = $this->requestdat['d_ext'];
                                $quit =  [ "Gte", "Gte.","Gerente", "GTE", "GTE.", "GERENTE", "Coord", "Coord.","COORD","COORD.","Coordinador","COORDINADOR","Coordinadora", "COORDINADORA","Subdir", "Subdir.","SUBDIR","SUBDIR.","SUBDIRECTOR", "SUBDIRECTORA","Sub-Director","SUB-DIRECTOR", "Sub-Directora","SUB-DIRECTORA"];
                                $jer = array_diff($jer, $quit);
                                $termino = explode(" ", $puesto_aut);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia minima de Director');
                                }
                            }],
                            'ficha_t' => 'required',
                            'empresa_t' => 'required',
                            'nombre_t' => 'required'
                        ]);
                    } 
                }
                break;
            case '1':
            case '2':
                $aut = (isset($this->requestdat['n_aut'])) ? $this->requestdat['n_aut'] : null;
                if ($aut == null){
                    if($this->requestdat['d_ext'] == 1){
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => ['required',function($attribute, $puesto_jefe, $fail){
                                $jer = $this->jerarquias;
                                $termino = explode(" ", $puesto_jefe);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia solicitada');
                                }
                            }]
                        ]);
                    }else{
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => ['required',function($attribute, $puesto_jefe, $fail){
                                $jer = $this->jerarquias;
                                $termino = explode(" ", $puesto_jefe);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia solicitada');
                                }
                            }],
                            'ficha_t' => 'required',
                            'empresa_t' => 'required',
                            'nombre_t' => 'required'
                        ]);
                    }
                }else{
                    //dd('212');
                    if($this->requestdat['d_ext'] == 1){
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => ['required'],
                            'n_aut' =>'required|integer',
                            'correo_aut' => 'required',
                            'puesto_aut' => ['required',function($attribute, $puesto_aut, $fail){
                                $jer = $this->jerarquias;
                                $termino = explode(" ", $puesto_aut);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia solicitada');
                                }
                            }]
                        ]);
                    }else{
                        $request->validate([
                            'n_empleado' => 'required|integer',
                            'mail_sol' => 'required',
                            'u_red' => 'required',
                            'n_jefe' => 'required',
                            'correo_jefe' => 'required',
                            'puesto_jefe' => ['required'],
                            'n_aut' =>'required|integer',
                            'correo_aut' => 'required',
                            'puesto_aut' => ['required',function($attribute, $puesto_aut, $fail){
                                $jer = $this->jerarquias;
                                $termino = explode(" ", $puesto_aut);
                                $val = array_search($termino[0], $jer);
                                if (!$val) {
                                    $fail('El usuario no cumple con la jerarquia solicitada');
                                }
                            }],
                            'ficha_t' => 'required',
                            'empresa_t' => 'required',
                            'nombre_t' => 'required'
                        ]);
                    }
                }
                break;
            case '3':
                $request->validate([
                    'n_empleado' => 'required|integer',
                    'mail_sol' => 'required',
                    'u_red' => 'required',
                    'n_jefe' => 'required',
                    'correo_jefe' => 'required',
                    'puesto_jefe' => ['required'],
                    'n_aut' =>'required|integer',
                    'correo_aut' => 'required',
                    'puesto_aut' => ['required',function($attribute, $puesto_aut, $fail){
                        $jer = $this->jerarquias;
                        $termino = explode(" ", $puesto_aut);
                        $val = array_search($termino[0], $jer);
                        if (!$val) {
                            $fail('El usuario no cumple con la jerarquia solicitada');
                        }
                    }]
                ]);
                break;
        }
        $emp = new EmpresaFilial;
        $nemp = Auth::user()->noEmployee;
        $a = new FusUserLogin;
        $b = new FusCapturistaModel;
        $user = $a->getByUser($nemp);
        $cap  = $b->registrarCapturista(Auth::user());
        $sol = array();

        $sol['no_empleado'] = $this->requestdat['n_empleado'];

        $correo_corporativo = ($this->requestdat['mail_sol'] !='') ? $this->requestdat['mail_sol'] : NULL;
        $sol['correo_corporativo'] = substr($correo_corporativo,0,149);

        $usuario_red = ($this->requestdat['u_red'] !='') ? $this->requestdat['u_red'] : NULL;
        $sol['usuario_red'] = substr($usuario_red,0,99);

        $nombre = ($this->requestdat['nombre'] !='') ? $this->requestdat['nombre'] : NULL;
        $sol['nombre'] = substr($nombre,0,49);

        $a_paterno = ($this->requestdat['a_paterno'] !='') ? $this->requestdat['a_paterno'] : NULL;
        $sol['a_paterno'] = substr($a_paterno,0,99);

        $a_materno = ($this->requestdat['a_materno'] !='') ? $this->requestdat['a_materno'] : NULL;
        $sol['a_materno'] = substr($a_materno,0,99);

        $puesto = ($this->requestdat['puesto'] !='') ? $this->requestdat['puesto'] : NULL;
        $sol['puesto'] = substr($puesto,0,254);

        $ubicacion_edificio = ($this->requestdat['ubi'] !='') ? $this->requestdat['ubi'] : NULL;
        $sol['ubicacion_edificio'] = substr($ubicacion_edificio,0,149);

        $tel_ext = ($this->requestdat['ext'] !='') ? $this->requestdat['ext'] : NULL;
        $sol['tel_ext'] = substr($tel_ext,0,5);

        $departamento = ($this->requestdat['dep'] !='') ? $this->requestdat['dep'] : NULL;
        $sol['departamento'] = substr($departamento,0,199);

        $centro_costos = ($this->requestdat['c_costos'] !='') ? $this->requestdat['c_costos'] : NULL;
        $sol['centro_costos'] = substr($centro_costos,0,199);

        $sol['vigencia'] = ($this->requestdat['vigencia_i'] !='') ? $this->requestdat['vigencia_i'] : NULL;
        
        $sol['no_empleado_jefe'] = ($this->requestdat['n_jefe'] !='') ? $this->requestdat['n_jefe'] : NULL;

        $correo_jefe = ($this->requestdat['correo_jefe'] !='') ? $this->requestdat['correo_jefe'] : NULL;
        $sol['correo_jefe'] = substr($correo_jefe,0,149);

        $sol['tipo_fus'] = ($this->requestdat['tipo_fus'] !='') ? $this->requestdat['tipo_fus'] : NULL;

        $p_jefe = ($this->requestdat['puesto_jefe'] !='') ? $this->requestdat['puesto_jefe'] : NULL;
        $sol['puesto_jefe'] = substr($p_jefe,0,254);

        $nombre_jefe = ($this->requestdat['nom_jefe'] !='') ? $this->requestdat['nom_jefe'] : NULL;
        $sol['nombre_jefe'] = substr($nombre_jefe,0,127);

        $apat_jefe =($this->requestdat['apat_jefe'] !='') ? $this->requestdat['apat_jefe'] : NULL;
        $sol['apat_jefe'] = substr($apat_jefe,0,127); 

        $amat_jefe = ($this->requestdat['amat_jefe'] !='') ? $this->requestdat['amat_jefe'] : NULL;
        $sol['amat_jefe'] = substr($amat_jefe,0,127); 

        $empresa_t = ($this->requestdat['empresa'] !='') ? $this->requestdat['empresa'] : NULL;
        $val = $emp->registrar_emp(substr($empresa_t,0,44));

        $sol['empresa_filial_id'] = $val;
        
        $sol['no_empleado_aut'] = ($this->requestdat['n_aut'] !='') ? $this->requestdat['n_aut'] : NULL;
        $aut_correo = ($this->requestdat['correo_aut'] !='') ? $this->requestdat['correo_aut'] : NULL;
        $sol['aut_correo'] = substr($aut_correo,0,149); 
        $aut_puesto = (isset($this->requestdat['puesto_aut'])) ? $this->requestdat['puesto_aut'] : NULL; 
        $sol['aut_puesto'] = substr($aut_puesto,0,254);
        $aut_nombre = (isset($this->requestdat['nom_aut'])) ? $this->requestdat['nom_aut'] : NULL;
        $sol['aut_nombre'] = substr($aut_nombre,0,127);
        $aut_apat = (isset($this->requestdat['apat_aut'])) ? $this->requestdat['apat_aut'] : NULL;
        $sol['aut_apat'] = substr($aut_apat,0,127);
        $aut_amat = (isset($this->requestdat['amat_aut'])) ? $this->requestdat['amat_aut'] : NULL;
        $sol['aut_amat'] = substr($aut_amat,0,127);
        
        $t_usu = (isset($this->requestdat['d_ext'])) ? $this->requestdat['d_ext'] : 1;
        if ($t_usu == 2) {
            $empresa_t = ($this->requestdat['empresa_t'] !='') ? $this->requestdat['empresa_t'] : NULL;
            $val_emp = $emp->registrar_emp(substr($empresa_t,0,44));
            $sol['ext_ficha'] = ($this->requestdat['ficha_t'] !='') ? $this->requestdat['ficha_t'] : NULL;
            $sol['ext_empresa'] = $val_emp;
            $ext_nombre = ($this->requestdat['nombre_t'] !='') ? $this->requestdat['nombre_t'] : NULL;
            $sol['ext_nombre'] = substr($ext_nombre,0,127);
            $ext_apat = ($this->requestdat['a_pat_t'] !='') ? $this->requestdat['a_pat_t'] : NULL;
            $sol['ext_apat'] = substr($ext_apat,0,127);
            $ext_amat = ($this->requestdat['a_mat_t'] !='') ? $this->requestdat['a_mat_t'] : NULL;
            $sol['ext_amat'] = substr($ext_amat,0,127);
            $ext_ubicacion = ($this->requestdat['ubicacion_t'] !='') ? $this->requestdat['ubicacion_t'] : NULL;
            $sol['ext_ubicacion'] = substr($ext_ubicacion,0,149);
            $ext_proyecto = ($this->requestdat['proyecto'] !='') ? $this->requestdat['proyecto'] : NULL;
            $sol['ext_proyecto'] = substr($ext_proyecto,0,254);
            $vigencia_ext = ($this->requestdat['vigencia'] !='') ? $this->requestdat['vigencia'] : NULL;
            $sol['ext_vigencia'] = substr($vigencia_ext,0,44);
        }
        $tipo_movimiento= (isset($this->requestdat['movimiento'])) ? $this->requestdat['movimiento'] : NULL;
        switch ($tipo_movimiento) {
            case 'Alta':
                $sol['tipo_movimiento'] = 1;
                break;
            case 'Baja':
                $sol['tipo_movimiento'] = 3;        
                break;
            case 'Cambio':
                $sol['tipo_movimiento'] = 2; 
                break; 
        }
        switch ($request->post('tipo_fus')) {
            case 0:
                $sol['clave_atencion'] = 0;
                break;
            case 1:
            case 2:
                date_default_timezone_set('America/Monterrey');
                $sol['autorizo_jefe'] = ($this->requestdat['n_aut'] !='') ? 1 : 0;
                $sol['fecha_auto_jefe'] = date('Y-m-d G:i:s');
            case 3:
                $sol['clave_atencion'] = 1;
                break;
            case 4:
            case 5:
                $sol['clave_atencion'] = 2;
                break;
            case 6:
                $sol['clave_atencion'] = 3;
                break;
        }

        $fus = new FUSSysadminWtl;
        $rcap = new RelFusCapturistaModel;
        $result = $fus->create_fus($sol);
        $rcap->RelFusCapturista($cap,$result->id);
        if ($request->file('archivo')) {
            $anexo = new RelAnexosFus;
            $fus = $result->id;
            $files = $request->file('archivo');
            foreach ($files as $key => $value) {
                $app = $this->requestdat['archivo'][$key]['app'];
                if(isset($value['file']))
                {
                    $arch=$value['file'];
                    $ext = $value['file']->getClientOriginalExtension();// recuperamos la extension del archivo
                    $filename  = 'anexo_' .$app.'_'.$fus.'_'.time().rand().'.' . $ext;
                    $path = $arch->storeAs('anexos', $filename);
                    $an = $anexo->guardar($filename,$result->id,$app);
                }
            }
        }
        $cap = Auth::user()->noEmployee;
        $soli = $this->requestdat['n_empleado'];
        if ($cap != $soli) {
            $notificacion = new NotificacionesController;
            $notificacion->sendMailNotificacionSolicitante($result->id);
        }
        $fus_w = array();
        switch ($request->post('tipo_fus')) {
            case 0:
                $fusAplicaciones = new FusSysadminController;
                $fusAplicaciones->store($request->post('aplicaciones'), $result->id);

                $notificacion = new NotificacionesController;
                $notificacion->sendMailAutorizacionJefe($result->id, 1);
                
                if($result->no_empleado_aut != "") {
                    $notificacion->sendMailAutorizacionJefe($result->id, 2);
                }

                break;
            case 1:
                $data = $this->requestdat;
                $fus_w= array(
                    "dominio" => array(
                        "etiqueta"=> "Dominio",
                        "valor"=> $this->requestdat['dominio']),
                    "movimiento" => array(
                        "etiqueta"=> "Tipo de operación",
                        "valor"=> $this->requestdat['movimiento'])
                    );
                $fus->update_fus($result->id,$fus_w);
                $notificacion = new NotificacionesController;
                $notificacion->sendMailAutorizacionJefe($result->id, 1);// correo para el jefe
                if($result->no_empleado_aut != "") {
                    $notificacion->sendMailAutorizacionJefe($result->id, 2);// correo para el autorizador
                } 
                break;
            case 2:
                $data = $this->requestdat;
                $fus_w= array(
                    "dominio" => array(
                        "etiqueta"=> "Dominio",
                        "valor"=> $this->requestdat['dominio']),
                    "smtp" => array(
                            "etiqueta"=> "SMTP",
                            "valor"=> $this->requestdat['smtp']),
                    "movimiento" => array(
                        "etiqueta"=> "Tipo de operación",
                        "valor"=> $this->requestdat['movimiento'])
                    );
                    $fus->update_fus($result->id,$fus_w);
                    $notificacion = new NotificacionesController;
                    $notificacion->sendMailAutorizacionJefe($result->id, 1);// correo para el jefe
                    if($result->no_empleado_aut != "") {
                        $notificacion->sendMailAutorizacionJefe($result->id, 2);// correo para el autorizador
                    } 
                break;
            case 3:
                $data = $this->requestdat;
                $fus_w= array(
                    "tipo_sol" => array(
                        "etiqueta"=> "Tipo de solicitud",
                        "valor"=> $this->requestdat['tipo_sol']),
                    "n_cuenta" => array(
                        "etiqueta"=> "Cuenta",
                        "valor"=> $this->requestdat['n_cuenta']),
                    "nombre_cuenta" => array(
                        "etiqueta"=> "Nombre de la cuenta",
                        "valor"=> $this->requestdat['nombre_cuenta']),
                    "f_vigencia" => array(
                        "etiqueta"=> "Fecha de vigencia",
                        "valor"=> $this->requestdat['f_vigencia']),
                    "dominio" => array(
                         "etiqueta"=> "Dominio",
                        "valor"=> (isset($this->requestdat['dominio'])) ? $this->requestdat['dominio'] : $this->requestdat['dominio2']),
                    "smtp" => array(
                        "etiqueta"=> "SMTP",
                        "valor"=> $this->requestdat['smtp']),
                    "justificacion" => array(
                        "etiqueta"=> "Justificacion",
                        "valor"=> $this->requestdat['justificacion']),
                    "movimiento" => array(
                        "etiqueta"=> "Tipo de operación",
                        "valor"=> $this->requestdat['movimiento'])
                    );
                $fus->update_fus($result->id,$fus_w);
                $notificacion = new NotificacionesController;
                $notificacion->sendMailAutorizacionJefe($result->id, 1);// correo para el jefe
                if($result->no_empleado_aut != "") {
                    $notificacion->sendMailAutorizacionJefe($result->id, 2);// correo para el autorizador
                } 
                break;
                case 4:
                    $data = $this->requestdat;
                    $fus_w= array(
                        "movimiento" => array(
                            "etiqueta"=> "Tipo de movimiento",
                            "valor"=> $this->requestdat['movimiento']),
                        "direc" => array(
                            "etiqueta"=> "Directorio o carpeta",
                            "valor"=> $this->requestdat['direc']),
                        "usuario" => array(
                            "etiqueta"=> "Nombre del autorizador",
                            "valor"=> $this->requestdat['usuario'])
                        );
                    $fus->update_fus($result->id,$fus_w);
                    $notificacion = new NotificacionesController;
                    $notificacion->sendMailAutorizacionJefe($result->id, 1);// correo para el jefe
                    if($result->no_empleado_aut != "") {
                        $notificacion->sendMailAutorizacionJefe($result->id, 2);// correo para el autorizador
                    } 
                    break;
            case 5:
                $data = $this->requestdat;
                $fus_w= array(
                    "movimiento" => array(
                        "etiqueta"=> "Tipo de solicitud",
                        "valor"=> $this->requestdat['movimiento']),
                    "so" => array(
                        "etiqueta"=> "Sistema Operativo",
                        "valor"=> $this->requestdat['so']),
                    "n_app" => array(
                        "etiqueta"=> "Nombre de aplicación o servicio",
                        "valor"=> $this->requestdat['n_app']),
                    "servidor" => array(
                        "etiqueta"=> "Servidor",
                        "valor"=> $this->requestdat['servidor']),
                    "ip" => array(
                        "etiqueta"=> "IP",
                        "valor"=> $this->requestdat['ip']),
                    "justificacion" => array(
                        "etiqueta"=> "Justificacion",
                        "valor"=> $this->requestdat['justificacion'])
                    );
                $fus->update_fus($result->id,$fus_w);
                $notificacion = new NotificacionesController;
                $notificacion->sendMailAutorizacionJefe($result->id, 1);// correo para el jefe
                if (isset($result->no_empleado_aut)) {
                    $notificacion->sendMailAutorizacionJefe($result->id, 2);// correo para el autorizador
                } 
                break;
            case 6:
                $data = $this->requestdat;
                $fus_w= array(
                    "num_usu" => array(
                        "etiqueta"=> "Numero de empleado",
                        "valor"=> $this->requestdat['num_usu']),
                    "ext_usu_red" => array(
                        "etiqueta"=> "Usuario de red",
                        "valor"=> $this->requestdat['ext_usu_red']),
                    "mail" => array(
                        "etiqueta"=> "Correo",
                        "valor"=> $this->requestdat['mail']),
                    "nom_proyecto" => array(
                        "etiqueta"=> "Proyecto",
                        "valor"=> $this->requestdat['nom_proyecto']),
                    "d_pro" => array(
                        "etiqueta"=> "Duracion del proyecto",
                        "valor"=> $this->requestdat['d_pro']),
                    "ext_ubicacion" => array(
                        "etiqueta"=> "Ubicacion",
                        "valor"=> $this->requestdat['ext_ubicacion']),
                    "impresion" => array(
                        "etiqueta"=> "Impresion",
                        "valor"=> (isset($this->requestdat['impresion'])) ? $this->requestdat['impresion'] : NULL),
                    "sarchivo" => array(
                        "etiqueta"=> "Servidor de archivos",
                        "valor"=> (isset($this->requestdat['sarchivo'])) ? $this->requestdat['sarchivo'] : NULL),
                    "saplicacion" => array(
                        "etiqueta"=> "Servidor de aplicaciones",
                        "valor"=> (isset($this->requestdat['saplicacion'])) ? $this->requestdat['saplicacion'] : NULL),
                    "marca" => array(
                        "etiqueta"=> "Marca",
                        "valor"=> $this->requestdat['marca']),
                    "modelo" => array(
                        "etiqueta"=> "Modelo",
                        "valor"=> $this->requestdat['modelo']),
                    "so" => array(
                        "etiqueta"=> "Sistema operativo",
                        "valor"=> $this->requestdat['so']),
                    "n_serie" => array(
                        "etiqueta"=> "No. serie",
                        "valor"=> $this->requestdat['n_serie']),
                    "t_equipo" => array(
                        "etiqueta"=> "Tipo de equipo",
                        "valor"=> $this->requestdat['t_equipo']),
                    "address" => array(
                        "etiqueta"=> "Mac Address",
                        "valor"=> $this->requestdat['address']),
                    "justificacion" => array(
                            "etiqueta"=> "Justificacion",
                            "valor"=> $this->requestdat['justificacion'])
                    );
                $fus->update_fus($result->id,$fus_w);
                $notificacion = new NotificacionesController;
                $notificacion->sendMailAutorizacionJefe($result->id, 1);// correo para el jefe
                if (isset($result->no_empleado_aut)) {
                    $notificacion->sendMailAutorizacionJefe($result->id, 2);// correo para el autorizador
                } 
                break;
        }
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se ha registrado un nuevo FUS',
            'tipo' => 'alta',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        return redirect()->route('fus_lista_despues', ["guardo" => 1, "folio" => $result->id]);
    }
    public function lista()
    {
        return view("fus_wintel.listafuses");
    }
    public function autocomplete(Request $request)
    {
        $term = $request->get('term', '');
        $search = $request->get('search', '');
        if (isset($search)) {
            $con2 = new ActiveDirectoriActive;
            $data = $con2->getEmployeeByNumEmpFw($term);
           
        }
        else{
            $con = new Comparelaboraconcilia;
            $consultas = $con->employeeByNumber($term);
            if(empty($term)) {
                return $data[] = array(
                    'response' => 'No se encontró el registro'
                );
            }        
            $data = array();
            foreach ($consultas as $val) {
                $data[]= array('numero'=>$val['employee_number'], 'nombre'=> str_replace("/", " ",$val['name']));
            }
        }
        if (count($data)) {
            return $data;
        } else if($data==null) {
            return $data[] = array('response'=>'No se encontró el registro');
        }    
    }
    public function autocomplete2(Request $request)
    {
        
        $term = (!isset($var)) ? $request->get('term') : 0;
        $term2 = ($request->get('type') == 1) ? $request->get('type') : null;
        $search = $request->get('search', '');
        $data = new generalModel;
        if($term == '0' || $term == 0) {
            return $result[] = array(
                'respuesta' => 'No se encontro el registro'
            );
        }else{
            $con2 = $data->db2();

            if ($search == 1) {    
                $result = $data->ejecutar_consulta($con2, $term, $search, $term2);   
            }
            else if ($search == 2) {
                $result = $data->ejecutar_consulta($con2, $term, $search, $term2);
            }else if ($search == 3) {
                $result = $data->ejecutar_consulta($con2, $term, 1);
            }

            if (count($result) > 0) {
                return $result;
            } else if($result==null) {
                return $result[] = array('respuesta'=>'No se encontro el registro');
            }
        }
    }
    public function autocomplete3(Request $request)
    {
        $var =$request->get('term', '');
        $term = (isset($var)) ? $request->get('term', '') : null;
        $search = $request->get('search', '');
        $data = new generalModel;
        if(empty($term)) {
            return $data[] = array(
                'response' => 'No se encontro el registro'
            );
        }
        $con2 = $data->db2();

        if ($search == 1) {    
            $result = $data->ejecutar_consulta($con2, $term, $search);   
        }
        else if ($search == 2) {
            $result = $data->ejecutar_consulta($con2, $term, $search);
        }else if ($search == 3) {
            $result = $data->ejecutar_consulta($con2, $term, 1);
        }

        if (count($result)) {
            return $result;
        } else if($result==null) {
            return $result[] = array('response'=>'No se encontro el registro');
        }
        // return $result;
    }
    public function validaruser(Request $request)
    {
        $term = $request->get('term', '');
        $result = new ActivedirectoryEmployees;
        $data = $result->getEmployeeByCuenta($term);
        if (count($data) > 0) {
            return 1;
        }
        else {
            return 0;
        }
    }
}
?>