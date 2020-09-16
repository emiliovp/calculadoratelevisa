<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Applications;
use App\ActivedirectoryEmployees;

class ControlConfigFuseApp extends Model
{
    protected $table = "fus_control_config_fuse_app";

    protected $fillable = [
        "id",
        "no_empleado",
        "usuario_red",
        "apps",
        "todo",
        "estado",
        "created_at",
        "updated_at"
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function usuarioByNoEmpName($noEmployee, $name) {
        return ControlConfigFuseApp::where("no_empleado", "=", $noEmployee)
        ->where("usuario_red", "=", $name)
        ->where("estado", "=", 1)
        ->first();
    }

    public function existenciaUsuarioByNoEmpName($noEmployee, $name) {
        $sql = ControlConfigFuseApp::where("no_empleado", "=", $noEmployee)
        ->where("usuario_red", "=", $name)
        ->where("estado", "=", 1)
        ->count();

        return $sql;
    }

    public function getlist() {
        $aplicaciones = new Applications;
        $ActivedirectoryEmployees = new ActivedirectoryEmployees;
        $sql = ControlConfigFuseApp::where("estado", "=", 1)->get()->toArray();
        $result = array();
        $count = 0; 

        foreach($sql AS $row => $value) {
            $result[$count]["id"] = $value["id"];
            $result[$count]["no_empleado"] = $value["no_empleado"];
            $result[$count]["usuario_red"] = $value["usuario_red"];

            if($value["todo"] == 1) {
                $result[$count]["apps"] = "Todas";
                $result[$count]["clavesapps"] = null;
            } else {
                $apps = json_decode($value["apps"], true);
                $appalias = "";
                foreach($apps AS $index => $val) {
                    $alias = $aplicaciones->getNameApplicationById($val);
                    $appalias .= $alias.", ";
                }
                $appalias = substr($appalias, 0, -2);
                $result[$count]["apps"] = $appalias;
                $result[$count]["clavesapps"] = str_replace(["[","]",'"','"', " "], "", $value["apps"]);
            }
            
            $result[$count]["nombre"] = $ActivedirectoryEmployees->getNameEmployeeByNumEmp($value["no_empleado"], $value["usuario_red"]);
            $result[$count]["estado"] = $value["estado"];
            $result[$count]["created_at"] = $value["created_at"];
            $result[$count]["updated_at"] = $value["updated_at"];

            $count = $count+1;
        }

        return $result;
    }

    public function eliminacionLogica($id) {
        $admonmesas = ControlConfigFuseApp::find($id);
        $admonmesas->estado = "Inactivo";
        $admonmesas->updated_at = DB::raw("now()");

        if($admonmesas->save()) {
            return true;
        }
        
        return false;
    }

    public function altaAdmonMesas($data) {
        $admonmesas = new ControlConfigFuseApp;
        $admonmesas->estado = "Activo";
        $admonmesas->no_empleado = $data["no_empleado"];
        $admonmesas->usuario_red = $data["usuario_red"];

        if($data["apps"] == 1) {
            $admonmesas->todo = $data["apps"];
        } else {
            $admonmesas->apps = json_encode($data["apps"]);
        }

        $admonmesas2 = ControlConfigFuseApp::where("no_empleado", "=", $data["no_empleado"])
        ->where("usuario_red", "=", $data["usuario_red"])
        ->where("estado", "=", 1)
        ->count();

        if($admonmesas2 == 0) {
            if($admonmesas->save()) {
                return true;
            }
            return false;
        } else {
            return "error_unique";
        }
    }
    
    public function actualizaAdmonMesas($data) {
        $admonmesas = ControlConfigFuseApp::find($data["id"]);
        $admonmesas->updated_at = DB::raw("now()");
        if($data["apps"] == 1) {
            $admonmesas->todo = $data["apps"];
            $admonmesas->apps = null;
        } else {
            $admonmesas->apps = json_encode($data["apps"]);
            $admonmesas->todo = null;
        }

        if($admonmesas->save()) {
            return true;
        }
        return false;
    }
}
