<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\LogBookMovements;
use Yajra\Datatables\Datatables;
use App\ControlConfigFuseApp;
use App\Applications;
use Illuminate\Support\Facades\Response;

class ControlconfigfuseappController extends Controller
{
    public $ip_address_client;

    public function __construct(){
        $this->ip_address_client = getIpAddress();
        $this->middleware('auth');
    }
    public function index(){
        $applications = new Applications;
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

        $apps = $applications->getApplications();
        return view('mesas.listacontrol')->with(["apps" => $apps]);
    }

    public function datalistacontrol() {
        $fus = new ControlConfigFuseApp;
        $result = $fus->getlist();
        
        return Datatables::of($result)->make(true);
    }

    public function bajaAdmonMesa(Request $request) {
        $admonmesa = new ControlConfigFuseApp;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        
        if($admonmesa->eliminacionLogica($request->post('id')) === true) {
            $msjDescription = 'Se ha realizado la eliminación del admon. de mesas de control '.$request->post("usuario_red");
            
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

    public function altaAdmonMesa(Request $request) {
        $admonmesa = new ControlConfigFuseApp;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $response = $admonmesa->altaAdmonMesas($request->post());

        if($response === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la alta de un admon. de mesas de control '.$request->post("usuario_red"),
                'tipo' => 'alta',
                'id_user' => $idEmployee
            );
            
            $bitacora = new LogBookMovements;
            $bitacora->guardarBitacora($data);

            return Response::json(true);
        } elseif($response == "error_unique") {
            return Response::json($response);
        } 

        return Response::json(false);
    }

    public function actualizaAdmonMesas(Request $request) {
        $admonmesa = new ControlConfigFuseApp;
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }

        $response = $admonmesa->actualizaAdmonMesas($request->post());

        if($response === true) {
            $data = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Se ha realizado la actualización de un admon. de mesas de control '.$request->post("usuario_red"),
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
