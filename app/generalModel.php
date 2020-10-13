<?php
namespace App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Adldap\Laravel\Facades\Adldap;
use App\ActivedirectoryEmployees;
use App\ActiveDirectoriActive;
class generalModel extends Model
{
    public function db2($con)
    {
        $db2 = null;
        $ip = $con['ip'];
        $prt = $con['port'];
        $usr = Crypt::decryptString($con['username']);
        $psw = Crypt::decryptString($con['pass']);        
        $name = Crypt::decryptString($con['namedb']);
        $cadena_con = $ip.":".$prt."/".$name;
        
        if (!$db2=@oci_connect($usr, $psw, $cadena_con)) {
            $responseError = array();
            $err = oci_error();
        }
        return $db2;
    }
    public function ejecutar_consulta($con2, $numero="", $tipo, $autocomplete = null)
    {
        if ($tipo == 1) {
            $sql = "select e.ficha, e.nombre, e.apellido, e.apellidom,    e.emp_clave, C.DESCRIP EMPRESA ,  e.org_clave,D.DESCRIP PROCESO ,  e.cve_centr, f.descrip ,  e.cve_depto CVE_DEPTO  , g.descrip DEPARTAMENTO  ,  e.folio,    e.extension,    e.ubicacion,    e.edificio,    e.vigencia fechavigencia ,
                e.cextra6 PUESTO  ,    e.cextra12 JEFE
                 from mov_emp e , empresa c, organismo d , cen_opera   f  , depto g 
                  where     e.emp_clave=  c.emp_clave and  E.EMP_CLAVE=d.EMP_CLAVE  and   e.org_clave=d.org_clave   and   e.emp_clave= f.emp_clave and e.org_clave=f.org_clave and e.cve_centr = f.cve_centr
                  and    e.emp_clave= g.emp_clave 
                  and e.org_clave=g.org_clave 
                  and e.cve_centr = g.cve_centr 
                  and e.cve_depto = g.cve_depto
                  and   to_number(e.ficha) like numero_t AND rownum <= 5";
        }
        elseif ($tipo == 2){
            $sql = "select  a.ficha, a.nombre, a.apellido , a.apellidoM, a.vigencia, a.ubicacion, a.cextra2 ACTIVIDA, b.empresa_ext EMPRESA, c.proyecto_ext   from mov_ext a , empresa_ext b , proyecto_ext c  where  a.empresa_extid=b.empresa_extid
                and  a.proyecto_extid= c.proyecto_extid and   to_number(a.ficha) like numero_t AND rownum <= 5";
        }
        if ($autocomplete == null) {
            $tag = str_replace("numero_t", "'%".$numero."%'", $sql);
        } else {
            $tag = str_replace("numero_t", "'".$numero."'", $sql);
        }
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
        while (($row = @oci_fetch_assoc($stid)) != false) {
            $data[] = $row;
        }
        oci_free_statement($stid);
        oci_close($con2);
        return $data;
    }
    public function b_ad($ad,$numero){
        $usr = $ad['default']['settings']['username'];
        $pass = $ad['default']['settings']['password'];
        $usrsoi = $ad['soi']['settings']['username'];
        $pass_soi = $ad['soi']['settings']['password'];
        $datos = array();
        if (Adldap::getProvider('default')->auth()->attempt($usr, $pass, $bindAsUser = true)) {
            $datos = Adldap::getProvider('default')->search()->where('employeeid', '=', $numero)->get();
            $datos = json_decode($datos, true);
        }
        if (count($datos) == 0){
            if (Adldap::getProvider('filial')->auth()->attempt($usr, $pass, $bindAsUser = true)){
                $datos = Adldap::getProvider('filial')->search()->where('employeeid', '=', $numero)->get();
                $datos = json_decode($datos, true);
            }
        }
        if (count($datos) == 0){
            if(Adldap::getProvider('tsm')->auth()->attempt($usr, $pass, $bindAsUser = true)){
            $datos = Adldap::getProvider('tsm')->search()->where('employeeid', '=', $numero)->get();
            $datos = json_decode($datos, true);
            }
        }
        if (count($datos) == 0){
            if(Adldap::getProvider('soi')->auth()->attempt($usrsoi, $pass_soi, $bindAsUser = true)){
                $datos = Adldap::getProvider('soi')->search()->where('employeeid', '=', $numero)->get();
                $datos = json_decode($datos, true);
            }
        }
        $data = array();
        if(isset($data)) {
            $data['mail'] = (isset($datos[0]['mail'][0])) ? $datos[0]['mail'] : NULL;
            $data['u_red'] = (isset($datos[0]['samaccountname'][0])) ? $datos[0]['samaccountname'] : NULL; 
        }
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
