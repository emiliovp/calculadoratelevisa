<?php
namespace App\Http\Controllers;

use App\ActivedirectoryEmployees;
use App\Comparelaboraconcilia;
use App\ActiveDirectoriActive;

use App\CalRelSolicitudCapturista;
use App\CalCapturista;

use App\LogBookMovements;
use App\Catalogosmodel;

use App\CalEmpresaFilial;
use App\SolicitudModelo;

use App\CalUserLogin;
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
use Illuminate\Support\Facades\Crypt;


class SolicitudController extends Controller
{
    protected $requestdat;
    /**
     *  Create a new controller instance.
     * 
     * @return void
     */
    public $jerarquias;
    public $conexion;
    public function __construct()
    {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->jerarquias = config('app.jerarquias');
        $this->dataLDAP = config('ldap.connections');
        $this->conexion = config('app.codeac');
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new generalModel;
        $conn = $this->conexion;
        $con2 = $data->db2($conn);
        $result = array();
        $datos = array();
        $result = $data->ejecutar_consulta($con2, Auth::user()->noEmployee, 1);
        if (count($result) > 0 ) {
            $nemp = ltrim($result[0]['FICHA'],'0');
            $mail = $data->b_ad($this->dataLDAP,$nemp);
            $result[0] += $mail;
        }else{
            $nemp = 0;
        }
        /*echo "<pre>";
        var_dump($result);
        echo "</pre>";
        die();*/
        $jer = $this->jerarquias;
        $jerar = base64_encode(json_encode($jer));
        $param['jer'] = $jerar;
        $param['data'] = $result;
        $view = "solicitud.alta";
        $param['route'] = "SolicitudController@stored";
        $param['tipo_fus'] = 1;
        $param['tipo'] = 1;
        /*$data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de alta de fus',
            'tipo' => 'vista',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);*/
        // $view = "fus_wintel.fusView";
        return view($view)->with("param", $param);
    }
    public function stored(Request $request)
    {
        $t_fus = $request->post('tipo_fus');
        $this->requestdat = $request->post();


        if($this->requestdat['d_ext'] == 1){
            $request->validate([
                'n_empleado' => 'required|integer',
                'mail_sol' => 'required',
                'u_red' => 'required',
                'n_jefe' => 'required',
                'correo_jefe' => 'required',
                'puesto_jefe' => 'required',
                'correo_aut' => 'required',
                'puesto_aut' => 'required',
                /*'puesto_jefe' => ['required',function($attribute, $puesto_jefe, $fail){
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
                }],*/
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
                'puesto_aut' => 'required',
                /*'puesto_aut' => ['required',function($attribute, $puesto_aut, $fail){
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
                }],*/
                'ficha_ter' => 'required',
                'empresa_t' => 'required',
                'nombre_t' => 'required'
            ]);
        }
        $emp = new CalEmpresaFilial;
        $nemp = Auth::user()->noEmployee;
        $a = new CalUserLogin;
        $b = new CalCapturista;
        $user = $a->getByUser($nemp);
        $cap  = $b->registrarCapturista(Auth::user());
        $sol = array();

        $sol['cal_no_empleado'] = $this->requestdat['n_empleado'];

        $correo_corporativo = ($this->requestdat['mail_sol'] !='') ? $this->requestdat['mail_sol'] : NULL;
        $sol['cal_correo'] = substr($correo_corporativo,0,149);

        $usuario_red = ($this->requestdat['u_red'] !='') ? $this->requestdat['u_red'] : NULL;
        $sol['cal_usuario_red'] = substr($usuario_red,0,99);

        $nombre = ($this->requestdat['nombre'] !='') ? $this->requestdat['nombre'] : NULL;
        $sol['cal_nombre'] = substr($nombre,0,49);

        $a_paterno = ($this->requestdat['a_paterno'] !='') ? $this->requestdat['a_paterno'] : NULL;
        $sol['cal_a_paterno'] = substr($a_paterno,0,99);

        $a_materno = ($this->requestdat['a_materno'] !='') ? $this->requestdat['a_materno'] : NULL;
        $sol['cal_a_materno'] = substr($a_materno,0,99);

        $puesto = ($this->requestdat['puesto'] !='') ? $this->requestdat['puesto'] : NULL;
        $sol['cal_puesto'] = substr($puesto,0,254);

        $ubicacion_edificio = ($this->requestdat['ubi'] !='') ? $this->requestdat['ubi'] : NULL;
        $sol['cal_ubicacion_edificio'] = substr($ubicacion_edificio,0,149);

        $tel_ext = ($this->requestdat['ext'] !='') ? $this->requestdat['ext'] : NULL;
        $sol['cal_tel_ext'] = substr($tel_ext,0,5);

        $departamento = ($this->requestdat['dep'] !='') ? $this->requestdat['dep'] : NULL;
        $sol['cal_departamento'] = substr($departamento,0,199);

        $centro_costos = ($this->requestdat['c_costos'] !='') ? $this->requestdat['c_costos'] : NULL;
        $sol['cal_centro_costos'] = substr($centro_costos,0,199);

        $sol['cal_vigencia'] = ($this->requestdat['vigencia_i'] !='') ? $this->requestdat['vigencia_i'] : NULL;
        
        $sol['cal_no_empleado_jefe'] = ($this->requestdat['n_jefe'] !='') ? $this->requestdat['n_jefe'] : NULL;

        $correo_jefe = ($this->requestdat['correo_jefe'] !='') ? $this->requestdat['correo_jefe'] : NULL;
        $sol['cal_correo_jefe'] = substr($correo_jefe,0,149);

        $sol['cal_catalogo_equipo_id'] = 1;   // ($this->requestdat['tipo_fus'] !='') ? $this->requestdat['tipo_fus'] : NULL;

        $p_jefe = ($this->requestdat['puesto_jefe'] !='') ? $this->requestdat['puesto_jefe'] : NULL;
        $sol['cal_puesto_jefe'] = substr($p_jefe,0,254);

        $nombre_jefe = ($this->requestdat['nom_jefe'] !='') ? $this->requestdat['nom_jefe'] : NULL;
        $sol['cal_nombre_jefe'] = substr($nombre_jefe,0,127);

        $apat_jefe =($this->requestdat['apat_jefe'] !='') ? $this->requestdat['apat_jefe'] : NULL;
        $sol['cal_apat_jefe'] = substr($apat_jefe,0,127); 

        $amat_jefe = ($this->requestdat['amat_jefe'] !='') ? $this->requestdat['amat_jefe'] : NULL;
        $sol['cal_amat_jefe'] = substr($amat_jefe,0,127); 

        $empresa_t = ($this->requestdat['empresa'] !='') ? $this->requestdat['empresa'] : NULL;
        $val = $emp->registrar_emp(substr($empresa_t,0,44));

        $sol['cal_empresa_filial_id'] = $val;
        
        $sol['cal_no_empleado_aut'] = ($this->requestdat['n_aut'] !='') ? $this->requestdat['n_aut'] : NULL;
        $aut_correo = ($this->requestdat['correo_aut'] !='') ? $this->requestdat['correo_aut'] : NULL;
        $sol['cal_aut_correo'] = substr($aut_correo,0,149); 
        $aut_puesto = (isset($this->requestdat['puesto_aut'])) ? $this->requestdat['puesto_aut'] : NULL; 
        $sol['cal_aut_puesto'] = substr($aut_puesto,0,254);
        $aut_nombre = (isset($this->requestdat['nom_aut'])) ? $this->requestdat['nom_aut'] : NULL;
        $sol['cal_aut_nombre'] = substr($aut_nombre,0,127);
        $aut_apat = (isset($this->requestdat['apat_aut'])) ? $this->requestdat['apat_aut'] : NULL;
        $sol['cal_aut_apat'] = substr($aut_apat,0,127);
        $aut_amat = (isset($this->requestdat['amat_aut'])) ? $this->requestdat['amat_aut'] : NULL;
        $sol['cal_aut_amat'] = substr($aut_amat,0,127);
        
        $t_usu = (isset($this->requestdat['d_ext'])) ? $this->requestdat['d_ext'] : 1;
        if ($t_usu == 2) {
            $empresa_t = ($this->requestdat['empresa_t'] !='') ? $this->requestdat['empresa_t'] : NULL;
            $val_emp = $emp->registrar_emp(substr($empresa_t,0,44));
            $sol['cal_ext_ficha'] = ($this->requestdat['ficha_t'] !='') ? $this->requestdat['ficha_t'] : NULL;
            $sol['cal_ext_empresa'] = $val_emp;
            $ext_nombre = ($this->requestdat['nombre_t'] !='') ? $this->requestdat['nombre_t'] : NULL;
            $sol['cal_ext_nombre'] = substr($ext_nombre,0,127);
            $ext_apat = ($this->requestdat['a_pat_t'] !='') ? $this->requestdat['a_pat_t'] : NULL;
            $sol['cal_ext_apat'] = substr($ext_apat,0,127);
            $ext_amat = ($this->requestdat['a_mat_t'] !='') ? $this->requestdat['a_mat_t'] : NULL;
            $sol['cal_ext_amat'] = substr($ext_amat,0,127);
            $ext_ubicacion = ($this->requestdat['ubicacion_t'] !='') ? $this->requestdat['ubicacion_t'] : NULL;
            $sol['cal_ext_ubicacion'] = substr($ext_ubicacion,0,149);
            $ext_proyecto = ($this->requestdat['proyecto'] !='') ? $this->requestdat['proyecto'] : NULL;
            $sol['cal_ext_proyecto'] = substr($ext_proyecto,0,254);
            $vigencia_ext = ($this->requestdat['vigencia'] !='') ? $this->requestdat['vigencia'] : NULL;
            $sol['cal_ext_vigencia'] = substr($vigencia_ext,0,44);
        }
        $tipo_movimiento= (isset($this->requestdat['movimiento'])) ? $this->requestdat['movimiento'] : NULL;
        switch ($tipo_movimiento) {
            case 'Alta':
                $sol['cal_tipo_movimiento'] = 1;
                break;
            case 'Baja':
                $sol['cal_tipo_movimiento'] = 3;        
                break;
            case 'Cambio':
                $sol['cal_tipo_movimiento'] = 2; 
                break; 
        }
        $sol_modelo = new SolicitudModelo;
        $rcap = new CalRelSolicitudCapturista;
        $result = $sol_modelo->create_sol($sol);
        $rcap->RelCalCapturista($cap,$result->id);
        /*if ($request->file('archivo')) {
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
        }*/
        $cap = Auth::user()->noEmployee;
        $soli = $this->requestdat['n_empleado'];
        if ($cap != $soli) {
            $notificacion = new NotificacionesController;
            $notificacion->sendMailNotificacionSolicitante($result->id);
        }
        $notificacion = new NotificacionesController;
        $notificacion->sendMailAutorizacionJefe($result->id, 1);
        
        if($result->no_empleado_aut != "") {
            $notificacion->sendMailAutorizacionJefe($result->id, 2);
        }
        /*$fus_w = array();
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se ha registrado un nuevo FUS',
            'tipo' => 'alta',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        */
        // return redirect()->route('fus_lista_despues', ["guardo" => 1, "folio" => $result->id]);
    }
    public function lista()
    {
        return view("fus_wintel.listafuses");
    }
    public function autocomplete(Request $request)
    { 
        $term = (!isset($var)) ? $request->get('term') : 0;
        $term2 = ($request->get('type') == 1) ? $request->get('type') : null;
        $search = $request->get('search', '');
        $data = new generalModel;
        //dd($term);
        if($term == '0' || $term == 0) {
            return $result[] = array(
                'respuesta' => 'No se encontro el registro'
            );
        }else{
            $conn = $this->conexion;
            $con2 = $data->db2($conn);
            if ($search == 1) {    
                $result = $data->ejecutar_consulta($con2, $term, $search, $term2);   
            }
            else if ($search == 2) {
                $result = $data->ejecutar_consulta($con2, $term, $search, $term2);
            }
            if (count($result) > 0) {
                return $result;
            } else if($result==null) {
                return $result[] = array('respuesta'=>'No se encontro el registro');
            }
        }
    }
    public function autocomplete2(Request $request){
        $data = new generalModel;
        $term = $request->get('n_emp');
        $result = $data->b_ad($this->dataLDAP,$term);
        //if (count($result) > 0) {
            return $result;
        /*}else{
            return $result[];
        }*/
    }
}
?>