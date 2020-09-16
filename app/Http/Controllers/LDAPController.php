<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\AdldapInterface;
use Adldap\Objects\Paginator;

use Adldap\Laravel\Facades\Adldap;

class LDAPController extends Controller
{
    public $dataLDAP;
    public function __construct() {
        $this->ip_address_client = getIpAddress();// EVP ip para bitacora
        $this->dataLDAP = config('ldap.connections');
    }
    public function index() {
        // $password = 'D3s@rroll02019';
        $usernamesoi = 'sysadminfuse';
        $passwordsoi = 'eyJpdiI6ImF5bjVkQ2dtU1QzUldWd1wvWlU5ekV3PT0iLCJ2YWx1ZSI6InZvQ0MxaEIrM1lOQlJrQ3Z1XC8wdUFnPT0iLCJtYWMiOiIyYjQwZWVmN2YwMTlmOTVmZGJiNTU1MmQ1N2FhNjU2ZjA5NGZmNjNjN2RhZjZjN2YwNmUyYjJhZDFlNzA4MWI4In0=';

        $username = 'sysadmindesarro';
        $password = 'eyJpdiI6IlByczFoMUJXZlB3SjhwQzh0WjZmcUE9PSIsInZhbHVlIjoiTHJtY2VXVG9oTHpsWU83cVVNeHB1dz09IiwibWFjIjoiNGMxNDY4YTMwMmNkMjMwMGNkNjY2NzBlNzI4YWIyYTkyODE2MzU4ZDI5MzBiYzIyMmQyZDdmNjQ5ZWRhNGUwZiJ9';
        
        try {
            if (Adldap::getProvider('soi')->auth()->attempt($usernamesoi, $passwordsoi, $bindAsUser = true)) {
                // $resultscorp = Adldap::getProvider('default')->search()->users()->limit(5)->get(); // Por cantidades
                $resultssoi = Adldap::getProvider('soi')->search()->where('samaccountname', '=', 'dsanchezl')->get(); // Por usuario mcesarg
            } else {
                $resultssoi = 'Failed SOI';
            }
            
            if (Adldap::getProvider('default')->auth()->attempt($username, $password, $bindAsUser = true)) {
                // $resultscorp = Adldap::getProvider('default')->search()->users()->limit(5)->get(); // Por cantidades
                $resultscorp = Adldap::getProvider('default')->search()->where('samaccountname', '=', 'dsanchezl')->get(); // Por usuario mcesarg
            } else {
                $resultscorp = 'Failed CORP';
            }
            if (Adldap::getProvider('filial')->auth()->attempt($username, $password, $bindAsUser = true)) {
                // $resultsfilial = Adldap::getProvider('filial')->search()->users()->limit(5)->get(); // Por cantidades
                $resultsfilial = Adldap::getProvider('filial')->search()->where('samaccountname', '=', 'dsanchezl')->get(); // Por usuario mcesarg
            } else {
                $resultsfilial = 'Failed FILIAL';
            }
            if (Adldap::getProvider('tsm')->auth()->attempt($username, $password, $bindAsUser = true)) {
                // $resultstsm = Adldap::getProvider('tsm')->search()->users()->limit(5)->get(); // Por cantidades
                $resultstsm= Adldap::getProvider('tsm')->search()->where('samaccountname', '=', 'dsanchezl')->get(); // Por usuario mcesarg
            } else {
                $resultstsm = 'Failed TSM';
            }
            // dd($resultsfilial);
            dd($resultssoi, $resultscorp, $resultsfilial, $resultstsm);
        } catch (Adldap\Auth\UsernameRequiredException $e) {
            // The user didn't supply a username.
            echo '<pre>';print_r($e);echo '</pre>';
        } catch (Adldap\Auth\PasswordRequiredException $e) {
            // The user didn't supply a password.
            echo '<pre>';print_r($e);echo '</pre>';
        }
        // die();
        // echo 'Usuario emagdale en corp<br>';
        // $results = Adldap::getProvider('default')->search()->users()->limit(5)->get(); // Por cantidades
        // $results = Adldap::getProvider('default')->search()->get(); // Trae todo
        // $results = Adldap::getProvider('default')->search()->where('samaccountname', '=', 'Lsuarezs')->get(); // Por usuario mcesarg
        // $results = Adldap::getProvider('default')->search()->where('samaccountname', '=', 'corpnone')->get(); // Por usuario mcesarg
        // $results = Adldap::getProvider('default')->search()->where('samaccountname', '=', 'emagdale')->get(); // Por usuario mcesarg
        // echo '<pre>';var_dump($results);echo '</pre>';
        // echo 'CORP<br>';
        // $results = Adldap::getProvider('default')->search()->users()->get();
        // echo '<pre>';var_dump($results);echo '</pre>';
        // echo 'Usuario emagdale en tsm<br>';
        // $results = Adldap::getProvider('tsm')->search()->get();
        // $results = Adldap::getProvider('tsm')->search()->where('samaccountname', '=', 'emagdale')->get();
        // echo '<pre>';var_dump($results);echo '</pre>';
        // echo 'TSM<br>';
        // $results = Adldap::getProvider('tsm')->search()->users()->get();
        // echo '<pre>';var_dump($results);echo '</pre>';
        // echo 'Usuario emagdale en filial<br>';
        // $results = Adldap::getProvider('filial')->search()->get();
        // $results = Adldap::getProvider('filial')->search()->where('samaccountname', '=', 'emagdale')->get();
        // echo '<pre>';var_dump($results);echo '</pre>';
        // echo 'FILIAL<br>';
        // $results = Adldap::getProvider('filial')->search()->users()->get();
        // echo '<pre>';var_dump($results);echo '</pre>';
        // die();

        // return view('welcome', [
        //     'users' => $ldap->search()->users()->get()
        // ]);
    }
    public function buscar(){
         return view('soporte.busquedaLdap');
    }
    public function getUsr(Request $request){
        $var =$request->get('term', '');
        $dom =$request->get('dom', '');
        $usr = $this->dataLDAP['default']['settings']['username'];
        $pass = $this->dataLDAP['default']['settings']['password'];
        $usrsoi = $this->dataLDAP['soi']['settings']['username'];
        $pass_soi = $this->dataLDAP['soi']['settings']['password'];
        //dd($usrsoi,$pass_soi);
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
            $nombre = (isset($datos[0]['displayname'])) ? $datos[0]['displayname'][0] : 'no asignado';
            $num_emp = (isset($datos[0]['employeeid'])) ? $datos[0]['employeeid'][0] : 'no asignado';
            $attrib10 = (isset($datos[0]['extensionattribute10'])) ? $datos[0]['extensionattribute10'][0] : 'no asignado';
            $attrib15 = (isset($datos[0]['extensionattribute15'])) ? $datos[0]['extensionattribute15'][0] : 'no asignado';

            $param['nombre']=$nombre;
            $param['number_emp']=$num_emp;
            $param['attrib10']=$attrib10;
            $param['attrib15']=$attrib15;
            echo json_encode($param);
        }
        else{
            echo json_encode($param['error'] = 0);
        }
    }
}