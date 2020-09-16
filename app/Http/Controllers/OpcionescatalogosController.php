<?php

namespace App\Http\Controllers;

use App\Applications;
use App\Op_cat_model;
use App\Catalogosmodel;
use App\LogBookMovements;
use App\FusConfiguracionesAutorizaciones;
use App\RelCatOpcionesConfiguracionesAutorizaciones;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class OpcionescatalogosController extends Controller
{
    public $ip_address_client;
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function index(Request $request) {
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
            'description' => 'Empleado con #'.$noEmployee.' visualizó lista de opciones de catálogo.',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        // $apps = $applicationsFus->getApplications();
        // 'apps' => $apps, 
        return view('catalogos.listaopciones')->with(['msjOk' => $msjOk, 'idapp' => $request->idapp, 'id' => $request->id]);
    }

    public function dataIndexOptCat(Request $request) {
        $optCat = new Op_cat_model;    
        $data = $optCat->getListaOpciones($request->id);
        return Datatables::of($data)->make(true);
    }

    public function verdependencias(Request $request) {
        $rcc = new RelCatOpcionesConfiguracionesAutorizaciones;
        return json_encode($rcc->getDependencias($request->post('idcatop')));
    }

    public function deleteoptcat(Request $request) {
        $optcatalogo = new Op_cat_model;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        
        if($optcatalogo->eliminacionLogica($request->post('id')) === true) {
            
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la eliminación de una opción de catálogo '.$request->post("id"),
                'tipo' => 'alta',
                'id_user' => $idEmployee
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);
            
            return Response::json(true);
        }

        return Response::json(false);
    }

    public function altaOpt(Request $request) {
        $cat = new Catalogosmodel;
        $opt = new Op_cat_model;
        $aut = new FusConfiguracionesAutorizaciones;
        $opciones = $opt->getOptCatalogoByIdApp($request->id);
        $autorizaciones = $aut->getConfiguracionesByIdApp($request->idapp);
        $catalogos= $cat->catalogosByApp($request->idapp);
        return view('catalogos.altaoptcat')->with(['autorizaciones' => $autorizaciones, 'catalogos_id' => $request->id, 'idapp' => $request->idapp, 'optcat' => $opciones, 'catalogos'=>$catalogos]);
    }
    
    public function editarOpt(Request $request) {
        $opt = new Op_cat_model;
        $cat = new Catalogosmodel;
        $aut = new FusConfiguracionesAutorizaciones;
        $opcionAEditar = $opt->getOptCatalogoByIdForFormEdit($request->idopt);

        $opciones = $opt->getOptCatalogoByIdApp($request->id);
        $autorizaciones = $aut->getConfiguracionesByIdApp($request->idapp);
        $catalogo = $cat->catalogosByApp($request->idapp);
        if(isset($opcionAEditar['cat_opciones_id'])){
            $padcat = $cat->catalogosByOpt($opcionAEditar['cat_opciones_id']);
            $catpadre = $padcat[0]['id'];
            $optpad = $opcionAEditar['cat_opciones_id'];
        }else{
            $catpadre = 0;
            $optpad = 0;
        }
        // dd($opcionAEditar['cat_opciones_id']);
        return view('catalogos.editaroptcat')->with(['opcionAEditar' => $opcionAEditar, 'autorizaciones' => $autorizaciones, 'catalogos_id' => $request->id, 'idapp' => $request->idapp, 'optcat' => $opciones,'catalogos'=>$catalogo,'padre'=>$catpadre,'oppad'=>$optpad]);
    }
    
    public function updateoptcat(Request $request) {
        $request->validate([
            "cat_op_descripcion" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
        ]);
        
        $optcat = new Op_cat_model;
        $aut = new FusConfiguracionesAutorizaciones;
        $relaciones = new RelCatOpcionesConfiguracionesAutorizaciones;

        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        if($request->post("cat_opciones_id")) {
            $getJerarDependencia = $optcat->getOptCatalogoById($request->post("cat_opciones_id"));
            $jerarquia = ($getJerarDependencia['jerarquia']+1);
            $cat_opciones_id = $request->post("cat_opciones_id");
        }  else {
            $jerarquia = 1;
            $cat_opciones_id = null;
        }
        
        $optcat->guardarEdicionOpt($request->post("idopt"), $request->post('cat_op_descripcion'), $request->post('catalogos_id'), $cat_opciones_id, $jerarquia);
        
        if($request->post("hiddenAutorizaciones")) {
            $relaciones->deleteByIdOptCat($request->post("idopt"));
            $idAutorizacionesSeleccionadas = explode("_", $request->post("hiddenAutorizaciones"));
            $todasLasAut = $aut->getAutorizacionesByIdappAndIdsAutsSeleccionadas($idAutorizacionesSeleccionadas, $request->post("idapp"));
            
            foreach($todasLasAut AS $key => $row) {
                $relaciones->guardarRelaciones($row['id'], $request->post("idopt"));
            }
        } else {
            $relaciones->deleteByIdOptCat($request->post("idopt"));
        }
                
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se ha realizado la alta del catálogo '.$request->post("name"),
            'tipo' => 'alta',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        
        return redirect()->route('listaopcionesok', ['id' => $request->post('catalogos_id'), 'idapp' => $request->post("idapp"), "msjOk" => 1]);
    }
    public function storeoptcat(Request $request) {
        $request->validate([
            "cat_op_descripcion" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
        ]);
        
        $optcat = new Op_cat_model;
        $aut = new FusConfiguracionesAutorizaciones;
        $relaciones = new RelCatOpcionesConfiguracionesAutorizaciones;

        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        if($request->post("cat_opciones_id")) {
            $getJerarDependencia = $optcat->getOptCatalogoById($request->post("cat_opciones_id"));
            $jerarquia = ($getJerarDependencia['jerarquia']+1);
            $cat_opciones_id = $request->post("cat_opciones_id");
        }  else {
            $jerarquia = 1;
            $cat_opciones_id = null;
        }
        
        $lastid = $optcat->guardarOpt($request->post('cat_op_descripcion'), $request->post('catalogos_id'), $cat_opciones_id, $jerarquia);
        
        if($request->post("hiddenAutorizaciones")) {
            $idAutorizacionesSeleccionadas = explode("_", $request->post("hiddenAutorizaciones"));
            $todasLasAut = $aut->getAutorizacionesByIdappAndIdsAutsSeleccionadas($idAutorizacionesSeleccionadas, $request->post("idapp"));
            
            foreach($todasLasAut AS $key => $row) {
                $relaciones->guardarRelaciones($row['id'], $lastid);
            }
        }
                
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Se ha realizado la alta del catálogo '.$request->post("name"),
            'tipo' => 'alta',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);
        
        return redirect()->route('listaopcionesok', ['id' => $request->post('catalogos_id'), 'idapp' => $request->post("idapp"), "msjOk" => 1]);
    }
    public function OptionByCatId(Request $request) {
        $op = new Op_cat_model; 
        $option = $op->optCatOpPadre($request->cat);
        print_r(json_encode($option));
   }
}
