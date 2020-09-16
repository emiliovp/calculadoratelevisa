<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LogBookMovements;
use App\Catalogosmodel;
use App\Applications;
use App\Op_cat_model;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CatalogosController extends Controller
{
    public $ip_address_client;
    
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $applicationsFus = new Applications;
        $noEmployee = (isset(Auth::user()->noEmployee) && Auth::user()->noEmployee != 0) ? Auth::user()->noEmployee : 1;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Empleado con #'.$noEmployee.' visualizó lista de Catálogos',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        
        $bitacora = new LogBookMovements;
        $bitacora->guardarBitacora($data);

        $apps = $applicationsFus->getApplications();

        return view('catalogos.listacatalogos')->with('apps', $apps);
    }

    public function dataIndexCat() {
        $catalogos = new Catalogosmodel;    
        $data = $catalogos->listCatalogos();
        return Datatables::of($data)->make(true);
    }

    public function storecat(Request $request) {
        $catalogo = new Catalogosmodel;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        
        $request->validate([
            "name" => "required|string|min:3|max:100|regex:/^[A-Za-z0-9[:space:]\s\S]+$/",
        ]);
        
        if($catalogo->altaCatalogo($request->post()) === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la alta del catálogo '.$request->post("name"),
                'tipo' => 'alta',
                'id_user' => $idEmployee
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);
            
            return Response::json(true);
        }

        return Response::json(false);
    }

    public function deletecat(Request $request) {
        $catalogo = new Catalogosmodel;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        
        if($catalogo->eliminacionLogica($request->post('id')) === true) {
            $msjDescription = 'Se ha realizado la eliminación del catálogo '.$request->post("cat_nombre");
            
            if(empty($request->post('aplicacion'))) {
                $msjDescription = 'Se ha realizado la eliminación del catálogo '.$request->post("cat_nombre").' con dependencia a la aplicación '.$request->post("aplicacion");
            }

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
    public function listaMesas(){
    
        return view('mesas.listamesas');
    } 
    public function getMesas(){
        $catalogos = new Catalogosmodel;    
        $result = $catalogos->catalogosByName('MESAS DE CONTROL');
        
        $data = array();
        if (count($result)>0) {
            $opciones = new Op_cat_model;
            $data = $opciones->getOptMesas($result[0]['id']);
        }
        return Datatables::of($data)->make(true);
    }
    public function bajamesas(Request $request){
        $id = $request->post('id');
        $opcionesmesas = new Op_cat_model;
        // dd($id);
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        
        if($opcionesmesas->eliminacionLogicaMesa($request->post('id')) === true) {
            $msjDescription = 'Se ha realizado la eliminación de la mesa con id '.$request->post("id");
            
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
    public function storemesa(Request $request){
        $id = $request->post('nombre');
        $opcionesmesas = new Op_cat_model;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        
        if($opcionesmesas->alta_mesa($request->post('nombre')) === true) {
            $msjDescription = 'Se ha realizado el alta de la mesa '.$request->post("nombre");
            
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
        $opcionesmesas = new Op_cat_model;
        $id = $request->post('id');
        $nombre = $request->post('nombre');
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        
        if($opcionesmesas->editMesa($id, $nombre)) {
            $msjDescription = 'Se ha realizado la edicion de la mesa '.$id;
            
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
}
