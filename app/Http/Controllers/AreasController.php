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
class AreasController extends Controller
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
          'description' => 'Empleado con #'.$noEmployee.' visualizó lista de areas.',
          'tipo' => 'vista',
          'id_user' => $idEmployee
          );
          $bitacora = new LogBookMovements;
          $bitacora->guardarBitacora($data);
          return view('areas.listaareas');
     }
     public function anyData(){
          $a = new AreaModel;
          $b = $a->listareatodas();
          return Datatables::of($b)->make(true);
     }
     public function storearea(Request $request){
          $area = $request->post('nombre');
          $a = new AreaModel;
          $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
          if($idEmployee == 0) {
              $idEmployee = null;
          }
          
          if($a->guardararea($request->post('nombre')) === true) {
              $msjDescription = 'Se ha realizado el alta del área '.$request->post("nombre");
              
              $data = array(
                  'ip_address' => $this->ip_address_client, 
                  'description' => $msjDescription,
                  'tipo' => 'alta',
                  'id_user' => $idEmployee
              );
              
              $bitacora = new LogBookMovements;
              $bitacora->guardarBitacora($data);
              
              return Response::json(true);
          }
  
          return Response::json(false);
     }
     public function editar(Request $request){
          $a = new AreaModel;
          $id = $request->post('id');
          $nombre = $request->post('nombre');
          $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
          
          if($idEmployee == 0) {
              $idEmployee = null;
          }
          
          if($a->editarea($id, $nombre)) {
              $msjDescription = 'Se ha realizado la edicion del area con el id '.$id;
              
              $data = array(
                  'ip_address' => $this->ip_address_client, 
                  'description' => $msjDescription,
                  'tipo' => 'edicion',
                  'id_user' => $idEmployee
              );
              
              $bitacora = new LogBookMovements;
              $bitacora->guardarBitacora($data);
              
              return Response::json(true);
          }
  
          return Response::json(false);
     }
     public function bloquear(Request $request){
          $a = new AreaModel;
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
          // dd($term);
          if($a->bloqueoarea($term,$mov) === true) {
              $msjDescription = 'Se ha puesto como '.$mov.' el área con id '.$request->post("id");
              
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