<?php
namespace App\Http\Controllers;

use App\AreaModel;
use App\modulosModel;
use App\FusUserLogin;
use App\PerfilesModel;
use App\LogBookMovements;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Export\FusExport;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers;
use App\Http\Controllers\FusSysadminController;
use App\Http\Controllers\NotificacionesController;

use Yajra\Datatables\Datatables;
class PerfilesController extends Controller
{
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
          $msjOk = 0;
          if(isset($request->msjOk)) {
               $msjOk = $request->msjOk;
          }
          $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
          $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
          if($idEmployee == 0) {
          $idEmployee = null;
          }

          $data = array(
          'ip_address' => $this->ip_address_client, 
          'description' => 'Empleado con #'.$noEmployee.' visualizó lista de perfiles.',
          'tipo' => 'vista',
          'id_user' => $idEmployee
          );
          
          $bitacora = new LogBookMovements;
          $bitacora->guardarBitacora($data);
          $a = new AreaModel;
          $b = $a->listarea();
          return view('perfiles.listaperfiles')->with(['msjOk' => $msjOk]);
     }
     public function anyData(){
          $a = new PerfilesModel;

          $b = $a->listperfiles();
          
          $data= array();
          foreach ($b as $key => $value) {
               $m = new modulosModel;
               $mod = json_decode($value['modulos_acceso'], true);
               $modulos_asignados = $m->modulosByName($mod);
               $data[$key]['id'] = $value['id'];
               $data[$key]['perfil'] = $value['perfil'];
               $data[$key]['area'] = $value['area'];
               $data[$key]['estado'] =$value['estado'];
               $data[$key]['fus_areas_perfiles_id'] = $value['fus_areas_perfiles_id'];
               $data[$key]['modulos_acceso'] = $modulos_asignados[0]['alias']; 
          }
          
          return Datatables::of($data)->make(true);
     }
     public function alta(){
          $a = new AreaModel;
          $b = $a->listarea();
          $mod = new modulosModel;
          $modulo = $mod->modulosActivos();
          return view('perfiles.altaperfiles')->with('area', $b)->with('modulo', $modulo);
     }
     public function store(Request $request){
          $area = $request->post('area');
          $nombreper = $request->post('perfil');
          $a = new PerfilesModel;
          $mod = new modulosModel;
          $modulos =explode('_',$request->post('hiddenModulos'));
          $modulo = $mod->modulosconcat($modulos);
          $mod= array();
          foreach ($modulo as $key => $value) {
               $mod[$key] = $value['modulo'];
          }
          $perfil['perfil'] = mb_strtoupper($nombreper);
          $perfil['modulos_acceso'] = json_encode($mod);;
          $perfil['fus_areas_perfiles_id'] = $area;
          $perfil['estado'] = 'Activo';
          $perfil['updated_at'] = NULL;
          // $val = json_encode($mod);
          // $data = $a->altaperfil($nombreper,$val,$area);
          $data = $a->altaperfil($perfil);
          $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
          if($idEmployee == 0) {
               $idEmployee = null;
           }
   
          $bit = array(
               'ip_address' => $this->ip_address_client, 
               'description' => 'Se ha realizado la alta del perfil '.$nombreper,
               'tipo' => 'alta',
               'id_user' => $idEmployee
          );
           
          $bitacora = new LogBookMovements;
          $bitacora->guardarBitacora($bit);
          return redirect()->route('listaperfilesok', ["msjOk" => 1]);
     }
     public function updatePerfil(Request $request){
          $a = new PerfilesModel;
          $mod = new modulosModel;
          $idperfil = $request->post('idperfil');
          $area = $request->post('area');
          $nombreper = $request->post('perfil');
          $modulos =explode('_',$request->post('hiddenModulos'));
          $modulo = $mod->modulosconcat($modulos);
          $mod= array();
          foreach ($modulo as $key => $value) {
               $mod[$key] = $value['modulo'];
          }
          $val = json_encode($mod);
          $data = $a->editperfil($idperfil,$nombreper,$val,$area);
          $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
          if($idEmployee == 0) {
               $idEmployee = null;
           }
   
          $bit = array(
               'ip_address' => $this->ip_address_client, 
               'description' => 'Se ha realizado la edición del perfil '.$nombreper,
               'tipo' => 'editar',
               'id_user' => $idEmployee
          );
           
          $bitacora = new LogBookMovements;
          $bitacora->guardarBitacora($bit);


          return redirect()->route('listaperfilesok', ["msjOk" => 1]);
     }
     public function editarPerfil($request){
          $term = $request;
          $a = new PerfilesModel;
          $m = new modulosModel;
          $area = new AreaModel;
          $b = $area->listarea();
          $moduloa_activos = $m->modulosActivos();
          $data = $a->perfilesById($term);
          if ($data[0]['modulos_acceso'] != null) {
               $mod= json_decode($data[0]['modulos_acceso'],true);
               $modulos_asignados = $m->modulosByName($mod);
               $extraerid= $modulos_asignados[0]['idmod'];
               $mod_select = explode("_", $extraerid);
          }else{
               $dato = $data[0]['modulos_acceso'] = NULL;
               $mod= json_decode($dato,true);
               $modulos_asignados = null;
               $mod_select = null;
          }
          // dd($data);
          return view('perfiles.editarperfiles')->with('area', $b)->with('modulo', $moduloa_activos)->with('perfil', $data)->with('modulos_select', $modulos_asignados)->with('modulosmarcar', $mod_select);
     }
     public function bloqueoPerfil(Request $request){
          $a = new PerfilesModel;
          $term = $request->post('id');
          $tipo = $request->post('tipo');
          $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
          if($idEmployee == 0) {
              $idEmployee = null;
          }
          if ($tipo== 1) {
               $mov = 'Activo';
          }else{
               $mov = 'Inactivo';

          }
          if($a->bloqueoPerfil($term,$mov) === true) {
              $msjDescription = 'Se ha puesto como '.$mov.' el perfil con id '.$request->post("id");
              
              $data = array(
                  'ip_address' => $this->ip_address_client, 
                  'description' => $msjDescription,
                  'tipo' => 'bloqueo',
                  'id_user' => $idEmployee
              );
              
              $bitacora = new LogBookMovements;
              $bitacora->guardarBitacora($data);
              
              return Response::json(true);
          }
          return Response::json(false);
     }
}
?>