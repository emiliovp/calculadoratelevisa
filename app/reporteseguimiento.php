<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

use App\tipofus_reporteseguimiento;
use App\app_reporteseguimiento;
use App\tipomovimiento_reporteseguimiento;

class reporteseguimiento extends Model
{
    protected $table = "reporteseguimiento";

    protected $fillable = [
        "folio",
        "tipo_fus",
        "tipo_movimiento",
        "fecha_creacion",
        "tipo_empleado",
        "solicitante",
        "jefe",
        "estado_jefe",
        "fecha_autorizacion_jefe",
        "autorizador",
        "estado_aut",
        "fecha_autorizacion_aut",
        "estado_fus",
        "app",
        "sox",
        "estado_app",
        "fecha_aut_app",
        "rol_mod_rep",
        "mesa_control",
        "mesa_control_estado",
        "mesa_control_fecha_autorizacion",
        "aut_adicional",
        "aut_adicional_estado",
        "aut_adicional_fecha_autorizacion",
        "ratificador",
        "ratificador_estado",
        "ratificador_fecha_autorizacion"
    ];

    protected $hidden = [];
    protected $primaryKey = 'folio';
    public $timestamps = false;

    public function reporteseguimiento($data = null) {
        if($data == null) {
            return reporteseguimiento::get()
            ->toArray();
        } else {
            // dd($data);
            $query = new reporteseguimiento;
            $condiciones = "";
            $tipoCond = "";
            $actWhere = 0;
            if(!empty($data["folio"])) {
                $condiciones = $tipoCond.' folio = '.$data['folio'];
                $actWhere = 1;
            }
            
            if(!empty($data["desde"]) && !empty($data["hasta"])) {
                if($actWhere == 1) {
                    $tipoCond = " AND";
                } else {
                    $actWhere = 1;
                }

                $condiciones .= $tipoCond.' fecha_creacion BETWEEN "'.$data["desde"].'" AND "'.$data["hasta"].'"';
            }

            if($data["tipofus"] != null && count($data["tipofus"]) > 0) {
                if($actWhere == 1) {
                    $tipoCond = " AND";
                } else {
                    $actWhere = 1;
                }

                $inTemp = "";

                foreach($data["tipofus"] AS $key => $value) {
                    if($value != null) {
                        $inTemp .= '"'.$value.'", ';
                    }
                }

                if($inTemp != "") {
                    $inTemp = substr($inTemp, 0, -2);

                    $condiciones .= $tipoCond.' tipo_fus IN('.$inTemp.')';
                }
            }
            if($data["tipomovimiento"] != null && count($data["tipomovimiento"]) > 0) {
                if($actWhere == 1) {
                    $tipoCond = " AND";
                } else {
                    $actWhere = 1;
                }
                
                $inTemp = "";

                foreach($data["tipomovimiento"] AS $key => $value) {
                    if($value != null) {
                        $inTemp .= '"'.$value.'", ';
                    }
                }

                if($inTemp != "") {
                    $inTemp = substr($inTemp, 0, -2);
    
                    $condiciones .= $tipoCond.' tipo_movimiento IN('.$inTemp.')';
                }
            }
            if($data["aplicacion"] != null && count($data["aplicacion"]) > 0) {
                if($actWhere == 1) {
                    $tipoCond = " AND";
                } else {
                    $actWhere = 1;
                }

                $inTemp = "";

                foreach($data["aplicacion"] AS $key => $value) {
                    if($value != null) {
                        $inTemp .= '"'.$value.'", ';
                    }
                }

                if($inTemp != "") {
                    $inTemp = substr($inTemp, 0, -2);

                    $condiciones .= $tipoCond.' app IN('.$inTemp.')';
                }
            }

            if($data["sox"] != "") {
                if($actWhere == 1) {
                    $tipoCond = " AND";
                } else {
                    $actWhere = 1;
                }
                if($data["sox"] == 0) {
                    $condiciones .= $tipoCond.' sox IS NULL ';
                } else {
                    $condiciones .= $tipoCond.' sox = '.$data["sox"];
                }
            }

            if(empty($condiciones)) {
                if(isset($data["total"]) && $data["total"] == 1) {
                    return $query::distinct("folio")->count("folio");
                }
                return $query::get()->toArray();
            } else {
                if(isset($data["total"]) && $data["total"] == 1) {
                    return $query::whereRaw($condiciones)->distinct("folio")->count("folio");
                }
                return $query::whereRaw($condiciones)->get()->toArray();
            }
        }
    }

    public function infoParaForm() {
        $result = array();

        $tipomovimientos = tipomovimiento_reporteseguimiento::select("tipo_movimiento")
        ->get()
        ->toArray();
        
        $apps = app_reporteseguimiento::select("app")
        ->get()
        ->toArray();

        $tipofus = tipofus_reporteseguimiento::select("tipo_fus")
        ->get()
        ->toArray();

        $result["tipomovimiento"] = $tipomovimientos;
        $result["tipofus"] = $tipofus;
        $result["apps"] =  $apps;

        return $result;
    }
}
