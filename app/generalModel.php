<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\ActivedirectoryEmployees;
use App\ActiveDirectoriActive;
class generalModel extends Model
{
    public function db2()
    {
        $db2 = null;
        $llave = 'inxdix_2018';
        $query = "
            SELECT name, rdbms_type, sox, hostname, ip_address, port
            , CAST(AES_DECRYPT(db_name, '$llave') AS CHAR(250)) DB_NAME
            , CAST(AES_DECRYPT(db_instance, '$llave') AS CHAR(250)) DB_INSTANCE
            , CAST(AES_DECRYPT(db_user, '$llave') AS CHAR(250)) DB_USER
            , CAST(AES_DECRYPT(db_psw, '$llave') AS CHAR(250)) DB_PSW
            FROM rdbms
            WHERE name = 'CODEAC'";
        $datos = DB::select(DB::raw($query));
        $tbd = $datos[0]->rdbms_type;
        $host = $datos[0]->hostname;
        $ip = $datos[0]->ip_address;
        $prt = $datos[0]->port;
        $usr = $datos[0]->DB_USER;
        $psw = $datos[0]->DB_PSW;
        $name = $datos[0]->DB_NAME;
        $conexionName = $datos[0]->name;
        $db2 ="";
        $cadena_con = $ip.":".$prt."/".$name;
        
        if (!$db2=@oci_connect($usr, $psw, $cadena_con)) {
            $responseError = array();
            $err = oci_error();
        }
        
        DB::disconnect('mysql');
        return $db2;
    }
    public function ejecutar_consulta($con2, $numero="", $tipo, $autocomplete = null)
    {
        if ($tipo == 1) {
            // $sql = "SELECT rq.*, r.rdbms_type, r.name FROM rdbms_qrys rq INNER JOIN rdbms r ON r.id = rq.rdbms_id WHERE rq.id=149"; //desarrollo
            $sql = "SELECT rq.*, r.rdbms_type, r.name FROM rdbms_qrys rq INNER JOIN rdbms r ON r.id = rq.rdbms_id WHERE rq.id=145"; // QA
        }
        elseif ($tipo == 2){
            // $sql = "SELECT rq.*, r.rdbms_type, r.name FROM rdbms_qrys rq INNER JOIN rdbms r ON r.id = rq.rdbms_id WHERE rq.id=148"; //desarrollo
            $sql = "SELECT rq.*, r.rdbms_type, r.name FROM rdbms_qrys rq INNER JOIN rdbms r ON r.id = rq.rdbms_id WHERE rq.id=146"; // QA
        }
        
        $consultas = DB::select(DB::raw($sql));
        DB::disconnect('mysql');
        if ($autocomplete == null) {
            $tag = str_replace("numero_t", "'%".$numero."%'", $consultas[0]->qry_read);
        } else {
            $tag = str_replace("numero_t", "'".$numero."'", $consultas[0]->qry_read);
        }
        $tag = str_replace(";", "", $tag);
        $stid = oci_parse($con2,$tag);
        if (!$stid) {
            $err = oci_error($con2);
            if(isset($err["code"]) && !empty($err["code"])){
                $responseError = array();
                $responseError["error"][$conexionName]["number"] = $err["code"];
                $responseError["error"][$conexionName]["msj"] = $err["message"];    
                return $responseError;
            }
        }
        if (!$r = @oci_execute($stid)) {
            $err = oci_error($con2);
            if(isset($err["code"]) && !empty($err["code"])){
                $responseError = array();
                $responseError["error"][$conexionName]["number"] = $err["code"];
                $responseError["error"][$conexionName]["msj"] = $err["message"]; 
                return $responseError;
            }
        }
        $data = array();
        $ad = new ActivedirectoryEmployees;
        $val = $ad->getEmployeeByNumEmpFw($numero);
        while (($row = @oci_fetch_assoc($stid)) != false) {
            $data[] = $row;
        }

        if(isset($data[0])) {
            $data[0]['mail'] = (isset($val[0]['correo'])) ? $val[0]['correo'] : NULL;
            $data[0]['u_red'] = (isset($val[0]['cuenta'])) ? $val[0]['cuenta'] : NULL; 
        }
        oci_free_statement($stid);
        oci_close($con2);
        
        return $data;
    }
    public static function recuperar_info($val)
    {
        $sql = "SELECT * FROM activedirectory_employees WHERE employee_number like '".$val."%'";
        $resultado = DB::select(DB::raw($sql));
        DB::disconnect('mysql');
        return $resultado;
    }
}
