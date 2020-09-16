<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\FusConfigAutOtro;
use App\ActiveDirectoriActive;
use App\LogBookMovements;
use App\Applications;
use App\MesaControl;
use App\CatFuses;
use App\FusConfiguracionesAutorizaciones;

use Yajra\Datatables\Datatables;
class ConfigautorizacionesOtrosController extends Controller
{
    public $ip_address_client;
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function index() {
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de alta de configuraciones de autorizaciones',
            'tipo' => 'vista',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );
        
        $appsConfiguraciones = new FusConfiguracionesAutorizaciones;

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        // return view('configautorizaciones.lista_confwtl')->with('appsConfig', $appsConfiguraciones->getConfiguraciones());
        return view('configautorizaciones.lista_confwtl');
    }

    public function alta() {
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la pantalla de alta de configuraciones de autorizaciones',
            'tipo' => 'Alta',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
         $cat_f = new CatFuses;
         $cat = $cat_f->recuperar_opciones();
        $cat_f = new CatFuses;
        $mesas = new MesaControl;
        
        return view('configautorizaciones.otra_alta')->with(['fuses' => $cat, 'mesasdecontrol' => $mesas->getMesaForAuto()]);
    }
    
    public function store(Request $request) {
        $dataHistorico = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se creo una nueva configuración de autorizaciones',
            'tipo' => 'alta',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );    
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($dataHistorico);
        $data = array();
        foreach($request->post('autorizadores') AS $row => $value) {
            
            $data[] = array(
                'numero_empleado' => $value['numemp'],
                'correo' => $value['email'], 
                'nombre' => $value['nombreemp'],
                'usuario_red' => (isset($value['samaccountname'])) ? strtolower($value['samaccountname']) : null,
                'estatus' => 1,
                'cat_fus_id' => (isset($value['t_fus'])) ? $value['t_fus'] : null,
                'tcs_cat_helpdesk_id' => (isset($value['idmesa'])) ? $value['idmesa'] : null
            );
        }

        if(FusConfigAutOtro::insert($data)) {
            return 'true';
        }

        return 'false';
    }

    public function searchEmployeeLabora(Request $request) {
        switch ($request->post('tipo')) {
            case 'usuario':
                $sql = new ActiveDirectoriActive;
                return $sql->getEmployeeByNumEmp($request->post('valor'));
                break;
            
            case 'mesacontrol':
                $sql = new MesaControl;
            return $sql->getMesaById($request->post('valor'));
                break;
        }
    }
    public function anyDAta()
    {
        $apps = new FusConfigAutOtro;
          $data = $apps->lista();
          return Datatables::of($data)->make(true);
    }
    public function baja(Request $request)
    {
        $auts = new FusConfigAutOtro;
        $val = $request->post('id');
        $auts->baja_logica($val);
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Baja de configuraciones de autorizaciones de operaciones',
            'tipo' => 'baja',
            'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );
        
        $appsConfiguraciones = new FusConfiguracionesAutorizaciones;

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        echo true;
    }
}