<?php
namespace App\Http\Controllers;

use App\FusUserLogin;
use App\generalModel;
use App\FUSSysadminWtl;
use App\Op_cat_model;
use App\LogBookMovements;
use App\Comparelaboraconcilia;
use App\ActiveDirectoriActive;
use App\RelConfigurationfussyswtl;
use App\FusConfiguracionesAutorizaciones;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Export\FusExport;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers;
use App\Http\Controllers\FusSysadminController;
use App\Http\Controllers\NotificacionesController;

use Yajra\Datatables\Datatables;
class FusGeneralController extends Controller
{
    public $ip_address_client;
    protected $requestdat;

    /**
     *  Create a new controller instance.
     * 
     * @return void
     */
     public function __construct()
     {
          $this->ip_address_client = getIpAddress();// EVP ip para bitacora
          $this->middleware('auth');
     }

     public function index(Request $request)
     {
        $configuracionesApps = new FusConfiguracionesAutorizaciones;
        
        $linkFusesPorAutorizar = $configuracionesApps->userInConfig(Auth::user()->name);
        $actLinkFusesXAutorizar = 0;
        
        if($linkFusesPorAutorizar > 0) {
          $actLinkFusesXAutorizar = 1;
        }
        if (Auth::user()->useradmin != 0) {
          $con = new FusUserLogin;
          $user = $con->getIdByNameUser(Auth::user()->name);
        }
        else {
          $user = null;
        }

        $term = '';
        if(isset($request->guardo)) {
          $term = $request->guardo;
        }
        $folio = '';
        if(isset($request->folio)) {
          $folio = $request->folio;
        }

        $data = array(
          'ip_address' => $this->ip_address_client, 
          'description' => 'Visualización de la pantalla de lista de FUS',
          'tipo' => 'vista',
          'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
        );

        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        return view('fus.fuses_lista')->with(['folio' => $folio, 'confirm' => $term, 'actLinkFusesXAutorizar'=> $actLinkFusesXAutorizar, 'tipo_usuario' => $user['tipo_user']]);
   }
   public function anyData()
   {
        $a = new FUSSysadminWtl;    
        $data = $a->recuperar_info(Auth::user()->noEmployee);

        return Datatables::of($data)->make(true);
   }
   public function listaFuses(Request $request) //listaFuses
   {
     $term = '';
     if(isset($request->guardo)) {
         $term = $request->guardo;
     }
 
      $a = new FusUserLogin;
      $emp = $a->getIdByNameUser(Auth::user()->name);
      
      $data = array(
           'ip_address' => $this->ip_address_client, 
           'description' => 'Visualización de la pantalla de lista por area de fus',
           'tipo' => 'vista',
           'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
      );
      $bitacora = new LogBookMovements;
      $bitacora->guardarBitacora($data);
      
      return view('fus.fusesListaArea')->with(['empleado' => $emp['tipo_user'], 'confirm' => $term]);
   }
   
   public function anydata2()
   {
        $userred = Auth::user()->name;
        $a = new FusUserLogin;
        $user = $a->getIdByNameUser($userred);
        $a = new FUSSysadminWtl;   
        
        switch ($user['tipo_user']) {
          case 'SYSADMIN':
               $clave = 0;
               break;
          case 'CAT':
               $clave = 1;
               break;
          case 'SEGURIDAD':
               $clave = 2;
               break;
          case 'SUTIS':
               $clave = 3;
               break;
        }

        $data = $a->recuperar_area($clave);
        return Datatables::of($data)->make(true);
   }

     public function listaAppsPorFus(Request $request) {
          $data = array(
               'ip_address' => $this->ip_address_client, 
               'description' => 'Visualización de la pantalla de lista de aplicaciones por fus',
               'tipo' => 'vista',
               'id_user' => (isset(Auth::user()->useradmin) && Auth::user()->useradmin != 0) ? Auth::user()->useradmin : 1
          );

          $bitacora = new LogBookMovements;
          $bitacora->guardarBitacora($data);

          $obtenerAppsAutorizacion = new RelConfigurationfussyswtl;
          $obtenerAppsAutorizacion->getAutorizacionesDeApps($request->idfus);

          return view('fus.apps_fus')->with('idfus', $request->idfus);
     }

     public function dataAppsFus(Request $request) {
          $apps = new RelConfigurationfussyswtl;
          $data = $apps->getAppsOfFus($request->idfus);
          return Datatables::of($data)->make(true);
     }
     public function get_option(Request $request) {
          $op = new Op_cat_model; 
          $option = $op->getOpciones($request->smtp);
          print_r(json_encode($option));
     }
     public function get_autorizadores(Request $request) {
          $op = new FusConfiguracionesAutorizaciones; 
          $option = $op->getConfiguracionesByIdApp($request->app);
          print_r(json_encode($option));
     }
     // public function export(){
     public function export($id){
          $a = new FUSSysadminWtl;
          $data = $a->exportExcel($id);
          $campos = array(
               'FirstName',
               'LastName',
               'DisplayName',
               'UserPrincipalName',	
               'extensionAttribute1',	
               'extensionAttribute4',	
               'extensionAttribute10',	
               'extensionAttribute11',
               'extensionAttribute15',
               'EmployeeID',	
               'Description',	
               'Office',	
               'telephoneNumber',	
               'Country',	
               'Title',	
               'Department',	
               'Company',	
               'DepartmentNumber',	
               'Manager',	
               'accountExpires',
               'Dominio',
               'SMTP');
          if ($data[0]['cve_fus'] == 1 || $data[0]['cve_fus'] == 2 || $data[0]['cve_fus'] == 3) {
               switch ($data[0]['cve_fus']) {
                    case 1:
                         $doc = 'sabana_de_creacion_de_cuenta.csv';
                         $t_fus_n = 'FUS  de solicitud de cuenta de red';
                         break;
                    case 2:
                         $doc = 'sabana_de_creacion_de_correo.csv';
                         $t_fus_n = 'FUS  de solicitud de correo';
                         break;
                    case 3:
                         $doc = 'sabana_de_creacion_de_cuenta_especial.csv';
                         $t_fus_n = 'FUS  de solicitud de cuenta especial';
                         // $t_fus['archivo']=$doc;
                    break;
               }
               $t_fus['nombre'] = $t_fus_n;
               $t_fus['archivo']=$doc;
               unset($data[0]['cve_fus']);
               Excel::store(new FusExport($data,$campos), $doc);
               return $t_fus;
          } else {
               return 0;
          }
     }
}
?>