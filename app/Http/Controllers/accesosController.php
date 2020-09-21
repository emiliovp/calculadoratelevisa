<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CalUserLogin;
use App\ControlConfigFuseApp;
use Illuminate\Support\Facades\Auth;

class accesosController extends Controller{
    public function __construct(){
        $this->middleware('auth');
    }

    public function validateAcceso($id,$modulo){
        $con = new CalUserLogin;

        $response = '';
        $val = $con->getDataUserByName(Auth::user()->name);
        $userred = $val[0]["cal_user_red"];
        $modulosPermitidos = json_decode($val[0]['cal_modulos_acceso'], true);
        $tipoUsuario = (isset($val[0]['tipo_user'])) ? $val[0]['tipo_user'] : null;
        
        if($modulosPermitidos != null) {
            if(in_array($modulo, $modulosPermitidos)) {
                $response = 'success';
            } else {
                $response = 'failed';
            }
        } else {
            if($modulo == "listafuses") {
                $response = 'success';
            } else {
                $response = 'failed';
            }
        }

        return response()->json([
            $response
        ]);
    }
}