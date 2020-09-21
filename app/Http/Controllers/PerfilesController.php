<?php
namespace App\Http\Controllers;

use App\CalAreasPerfiles;
use App\CalModulosAcceso;
use App\FusUserLogin;
use App\Calperfiles;
use App\CalLogBookMovements;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
// use App\Export\FusExport;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers;
// use App\Http\Controllers\FusSysadminController;
// use App\Http\Controllers\NotificacionesController;

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
          
          $bitacora = new CalLogBookMovements;
          $bitacora->guardarBitacora($data);
          $a = new CalAreasPerfiles;
          $b = $a->listarea();
          return view('perfiles.listaperfiles')->with(['msjOk' => $msjOk]);
     }
     public function anyData(){
          $a = new Calperfiles;

          $b = $a->listperfiles();
          
          $data= array();
          foreach ($b as $key => $value) {
               $m = new CalModulosAcceso;
               $mod = json_decode($value['cal_modulos_acceso'], true);
               $modulos_asignados = $m->modulosByName($mod);
               $data[$key]['id'] = $value['id'];
               $data[$key]['perfil'] = $value['cal_perfil'];
               $data[$key]['area'] = $value['cal_area'];
               $data[$key]['estado'] =$value['cal_estado'];
               $data[$key]['fus_areas_perfiles_id'] = $value['cal_areas_perfiles_id'];
               $data[$key]['modulos_acceso'] = $modulos_asignados[0]['alias']; 
          }
          
          return Datatables::of($data)->make(true);
     }
     public function alta(){
          $a = new CalAreasPerfiles;
          $b = $a->listarea();
          $mod = new CalModulosAcceso;
          $modulo = $mod->modulosActivos();
          return view('perfiles.altaperfiles')->with('area', $b)->with('modulo', $modulo);
     }
     public function store(Request $request){
          $area = $request->post('area');
          $nombreper = $request->post('perfil');
          $a = new Calperfiles;
          $mod = new CalModulosAcceso;
          $modulos = explode('_',$request->post('hiddenModulos'));
          $modulo = $mod->modulosconcat($modulos);
          $mod = array();
          
          foreach ($modulo as $key => $value) {
               $mod[$key] = $value['cal_modulos'];
          }
          
          $perfil['cal_perfil'] = mb_strtoupper($nombreper);
          $perfil['cal_modulos_acceso'] = json_encode($mod);;
          $perfil['cal_areas_perfiles_id'] = $area;
          $perfil['cal_estado'] = 'Activo';
          $perfil['updated_at'] = NULL;
          
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
           
          $bitacora = new CalLogBookMovements;
          $bitacora->guardarBitacora($bit);

          return redirect()->route('listaperfilesok', ["msjOk" => 1]);
     }
     public function updatePerfil(Request $request){
          $a = new Calperfiles;
          $mod = new CalModulosAcceso;
          $idperfil = $request->post('idperfil');
          $area = $request->post('area');
          $nombreper = $request->post('perfil');
          $modulos =explode('_',$request->post('hiddenModulos'));
          $modulo = $mod->modulosconcat($modulos);
          $mod= array();
          foreach ($modulo as $key => $value) {
               $mod[$key] = $value['cal_modulos'];
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
           
          $bitacora = new CalLogBookMovements;
          $bitacora->guardarBitacora($bit);


          return redirect()->route('listaperfilesok', ["msjOk" => 1]);
     }
     public function editarPerfil($request){
          $term = $request;
          $a = new Calperfiles;
          $m = new CalModulosAcceso;
          $area = new CalAreasPerfiles;
          $b = $area->listarea();
          $moduloa_activos = $m->modulosActivos();
          $data = $a->perfilesById($term);
          if ($data[0]['cal_modulos_acceso'] != null) {
               $mod= json_decode($data[0]['cal_modulos_acceso'],true);
               $modulos_asignados = $m->modulosByName($mod);
               $extraerid= $modulos_asignados[0]['idmod'];
               $mod_select = explode("_", $extraerid);
          }else{
               $dato = $data[0]['cal_modulos_acceso'] = NULL;
               $mod= json_decode($dato,true);
               $modulos_asignados = null;
               $mod_select = null;
          }
          // dd($data);
          return view('perfiles.editarperfiles')->with('area', $b)->with('modulo', $moduloa_activos)->with('perfil', $data)->with('modulos_select', $modulos_asignados)->with('modulosmarcar', $mod_select);
     }
     public function bloqueoPerfil(Request $request){
          $a = new Calperfiles;
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
              
              $bitacora = new CalLogBookMovements;
              $bitacora->guardarBitacora($data);
              
              return Response::json(true);
          }
          return Response::json(false);
     }
}
?>