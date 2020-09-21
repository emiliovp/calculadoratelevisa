<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Adldap\Laravel\Facades\Adldap;
use App\CalCalLogBookMovements;
use App\CalUserLogin;
use App\Calperfiles;
use Yajra\Datatables\Datatables;
class UserController extends Controller
{
    public $ip_address_client;
    public $dataLDAP;
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->dataLDAP = config('ldap.connections');
        $this->middleware('auth');
    }
    public function index(){
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        $bit = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Visualización de la lista de usuarios administradores de fus',
            'tipo' => 'vista',
            'id_user' => $idEmployee
        );
        $bitacora = new CalLogBookMovements;
        $bitacora->guardarBitacora($bit);
        return view('usuarios.usuarios_lista')->with(['alat' => 0]);
    }
    public function anyDAta()
    {
        $a = new CalUserLogin;
        $idEmployee = $a->getIdByNameUser(Auth::user()->email);
        
        if(!isset($idEmployee)) {
            $idEmployee = null;
        }
        $a = new CalUserLogin;
        if ($idEmployee['perfil'] == 'root') {
            $data = $a->getUserActiveroot($idEmployee['tipo_user']);
        }else {
            $data = $a->getUserActive($idEmployee['tipo_user']);
        }

        return Datatables::of($data)->make(true);
    }
    public function bajaUsr(Request $request){
        $a = new CalUserLogin;
        $val = $request->post('id');
        $a->bajaUsr($val);
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        $data = array(
            'ip_address' => $this->ip_address_client, 
            'description' => 'Baja de usuario de fus con id '.$val,
            'tipo' => 'baja',
            'id_user' => $idEmployee
        );
        $bitacora = new CalLogBookMovements;
        $bitacora->guardarBitacora($data);
        echo true;
    }
    public function setusuario(){
        $a = new CalUserLogin;
        $b = new PerfilesModel;
        $idEmployee = $a->getIdByNameUser(Auth::user()->email);

        if ($idEmployee['perfil'] == 'root') {
            $perfiles = $b->perfilesByArea($idEmployee['fus_areas_perfiles_id'], $idEmployee['perfil']);
        }else {
            $perfiles = $b->perfilesByArea($idEmployee['fus_areas_perfiles_id'], $idEmployee['perfil']);
        }
        // dd($idEmployee);
        // if(!isset($idEmployee)) {
        //     $idEmployee['tipo_user'] = null;
        // }  
             
        return view('usuarios.newuser')->with(['perfil' => $perfiles]);
    }
    public function store(Request $request){
        $request->validate([
            'u_red' => 'required',
            // 'n_empleado' =>'required',
            'perfil' => 'required|integer',
            'dominio' => 'required',
        ]);
        $data = array();
        $data[] = array(
            'num_employee' => $request->post('n_empleado'),
            'user_red' => $request->post('u_red'),
            'fus_perfiles_id' => $request->post('perfil')
        );
        $idEmployee = getIdUserLogin(Auth::user()->noEmployee);
        
        if($idEmployee == 0) {
            $idEmployee = null;
        }
        if(CalUserLogin::insert($data)) {
            $bit = array(
                'ip_address' => $this->ip_address_client, 
                'description' => 'Alta de usuario administrador de fus',
                'tipo' => 'vista',
                'id_user' => $idEmployee
            );
            $bitacora = new CalLogBookMovements;
            $bitacora->guardarBitacora($bit);
            return view('usuarios.usuarios_lista')->with(['alat' => 1]);
        }

        return 'false';
    }
    public function getUsr(Request $request){
        $buscarExistencia = new CalUserLogin;
        $var =$request->get('term', '');
        $dom =$request->get('dom', '');
        $existencia = $buscarExistencia->getUserExisByUsername($var);
        if($existencia > 0) {
            echo "false";
            exit();
        }
        $usr = $this->dataLDAP['default']['settings']['username'];
        $pass = $this->dataLDAP['default']['settings']['password'];
        $usrsoi = $this->dataLDAP['soi']['settings']['username'];
        $pass_soi = $this->dataLDAP['soi']['settings']['password'];
        $datos = array();
        switch ($dom) {
            case 1:
                if (Adldap::getProvider('default')->auth()->attempt($usr, $pass, $bindAsUser = true)) {
                    $datos = Adldap::getProvider('default')->search()->where('samaccountname', '=', $var)->get();
                    $datos = json_decode($datos, true);
                }
                if (count($datos) == 0){
                    if (Adldap::getProvider('filial')->auth()->attempt($usr, $pass, $bindAsUser = true)){
                        $datos = Adldap::getProvider('filial')->search()->where('samaccountname', '=', $var)->get();
                        $datos = json_decode($datos, true);
                    }
                }
                if (count($datos) == 0){
                    if(Adldap::getProvider('tsm')->auth()->attempt($usr, $pass, $bindAsUser = true)){
                    $datos = Adldap::getProvider('tsm')->search()->where('samaccountname', '=', $var)->get();
                    $datos = json_decode($datos, true);
                    }
                }
                break;
            
            case 2:
                if (count($datos) == 0){
                    if(Adldap::getProvider('soi')->auth()->attempt($usrsoi, $pass_soi, $bindAsUser = true)){
                        $datos = Adldap::getProvider('soi')->search()->where('samaccountname', '=', $var)->get();
                        $datos = json_decode($datos, true);
                        }
                }
                break;
        }
        $param = array();
        if (isset($datos) && count($datos) >0) {
            if(isset($datos[0]['extensionattribute10'][0]) && $datos[0]['extensionattribute10'][0] == 'I')
            {
                if (isset($datos[0]['employeeid'])) {
                    $nombre = $datos[0]['displayname'];
                    $num_emp = $datos[0]['employeeid'];
                    $param['nombre']=$nombre[0];
                    $param['number_emp']=$num_emp[0];
                    echo json_encode($param);
                }else{
                    echo json_encode($param['error'] = 1);
                }
            }else{
                    $nombre = $datos[0]['displayname'];
                    $num_emp = (isset($datos[0]['employeeid'])) ? $datos[0]['employeeid'][0] : 0;
                    $param['nombre']=$nombre[0];
                    $param['number_emp']=$num_emp;
                    echo json_encode($param);
            }
        }
        else{
            echo json_encode($param['error'] = 0);
        }
    }
}
