<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Op_cat_model;
use App\ControlConfigFuseApp;
use App\RelCatOpcionesConfiguracionesAutorizaciones;

class FusConfiguracionesAutorizaciones extends Model
{
    protected $table = "fus_configuracion_autorizaciones";

    protected $fillable = [
        'id',
        'correo', 
        'no_empleado_labora', 
        'nombre_labora', 
        'usuario_red', 
        'tipo_autorizacion',
        'rol_mod_rep', 
        'estado', 
        'applications_id', 
        'tcs_cat_helpdesk_id',
        'claveapp_temp',
        'tipo_autorizador',
        'id_usr_alta',
        'id_usr_cambio',
        'created_at', 
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public $apps = "";
    
    public $desde="";
    public $hasta="";
    public $tipoaut="";
    public $app="";
    public $responsabilidad="";
    public $user="";
    public $estatus="";

    public function getOtros($idapp) {
        $query = FusConfiguracionesAutorizaciones::select('id')
        ->where('applications_id', '=', $idapp)
        ->where('tipo_autorizacion', '=', 5)
        ->get()
        ->toArray();

        $result = array();

        foreach($query AS $row) {
            $result[] = $row['id'];
        }

        return $result;
    }

    public function userInConfig($username) {
        return FusConfiguracionesAutorizaciones::where('usuario_red', '=', $username)->count();
    }

    public function getConfiguracionAutorizaciones($clave, $tipo, $idext = null, $tipocatalogo = null) {
        if($idext != null && is_numeric($idext)) {
            $countCatOp = RelCatOpcionesConfiguracionesAutorizaciones::where('cat_opciones_id', '=', $idext)
            ->count();
            
            if($countCatOp > 0) {
                return RelCatOpcionesConfiguracionesAutorizaciones::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id')
                ->where('rel_catopciones_configuracionesautorizaciones.cat_opciones_id', '=', $idext)
                ->where('fus_configuracion_autorizaciones.estado', '=', '1')
                ->groupby('fus_configuracion_autorizaciones.rol_mod_rep')
                ->orderby('fus_configuracion_autorizaciones.rol_mod_rep', 'asc')
                ->get()
                ->toArray();
            }
        } else if($idext != null && $tipocatalogo == 'sinsubopciones') {
            $idRel = Op_cat_model::where('cat_op_descripcion', '=', $idext)
            ->first()
            ->toArray();
            
            $configConSubCat = RelCatOpcionesConfiguracionesAutorizaciones::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id')
            ->where('rel_catopciones_configuracionesautorizaciones.cat_opciones_id', '=', $idRel['id'])
            ->get()
            ->toArray();
            $idsconfigConSubCat = array();
            foreach($configConSubCat AS $rows) {
                array_push($idsconfigConSubCat, $rows['id']);
            }
            
            return FusConfiguracionesAutorizaciones::whereNotIn('id', $idsconfigConSubCat)
            ->where('tipo_autorizacion', '=', $tipo)
            ->where('estado', '=', '1')
            ->where('applications_id', '=', $clave)
            ->orderby('rol_mod_rep', 'asc')
            ->get()
            ->toArray();

        } else if($idext != null) {
            $idRel = Op_cat_model::where('cat_op_descripcion', '=', $idext)
            ->first()
            ->toArray();

            return RelCatOpcionesConfiguracionesAutorizaciones::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id')
            ->where('rel_catopciones_configuracionesautorizaciones.cat_opciones_id', '=', $idRel['id'])
            ->where('fus_configuracion_autorizaciones.estado', '=', '1')
            ->orderby('fus_configuracion_autorizaciones.rol_mod_rep', 'asc')
            ->get()
            ->toArray();
        }
        if($tipocatalogo != null) {

            switch ($clave) {
                case 16:
                case 20:
                case 64:
                case 1032:
                    $optCat = Op_cat_model::select('cat_opciones.id')
                        ->join('catalogos', 'catalogos.id', '=', 'cat_opciones.catalogos_id')
                        ->where('catalogos.applications_id', '=', $clave)
                        ->where('cat_opciones.cat_op_descripcion', '=', $tipocatalogo)
                        ->first()
                        ->toArray();
                    return FusConfiguracionesAutorizaciones::select(['fus_configuracion_autorizaciones.rol_mod_rep', 'fus_configuracion_autorizaciones.id'])
                        ->join('rel_catopciones_configuracionesautorizaciones', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id', '=', 'fus_configuracion_autorizaciones.id')
                        ->where('fus_configuracion_autorizaciones.rol_mod_rep', 'NOT LIKE', "%\%\%\%%")
                        ->where('fus_configuracion_autorizaciones.applications_id', '=', $clave)
                        ->where('fus_configuracion_autorizaciones.tipo_autorizacion', '=', $tipo)
                        ->where('fus_configuracion_autorizaciones.estado', '=', '1')
                        ->where('rel_catopciones_configuracionesautorizaciones.cat_opciones_id', '=', $optCat['id'])
                        ->groupby('fus_configuracion_autorizaciones.rol_mod_rep')
                        ->orderby('fus_configuracion_autorizaciones.rol_mod_rep', 'asc')
                        ->get();
                    break;
                default:
                    $optCat = Op_cat_model::where('cat_opciones.cat_op_descripcion', '=', $tipocatalogo)
                        ->first()
                        ->toArray();

                    return RelCatOpcionesConfiguracionesAutorizaciones::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id')
                        ->where('rel_catopciones_configuracionesautorizaciones.cat_opciones_id', '=', $optCat['id'])
                        ->where('fus_configuracion_autorizaciones.estado', '=', '1')
                        ->groupby('fus_configuracion_autorizaciones.rol_mod_rep')
                        ->orderby('fus_configuracion_autorizaciones.rol_mod_rep', 'asc')
                        ->get()
                        ->toArray();
                    break;
            }
        }
        
        $query = FusConfiguracionesAutorizaciones::where('applications_id', '=', $clave)
            ->where('tipo_autorizacion', '=', $tipo)
            ->where('estado', '=', '1')
            ->groupby('rol_mod_rep')
            ->orderby('rol_mod_rep', 'asc')
            ->get();
        
        return $query;
    }

    public function getIdsForFus($ids) {
        $query = FusConfiguracionesAutorizaciones::from('fus_configuracion_autorizaciones')
        ->select([
            'fus_configuracion_autorizaciones.applications_id', 
            'fus_configuracion_autorizaciones.rol_mod_rep',
            'cat_opciones.id AS idCatOpt',
            'cat_opciones.cat_opciones_id'
        ])
        ->leftJoin('rel_catopciones_configuracionesautorizaciones', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id', '=', 'fus_configuracion_autorizaciones.id')
        ->leftJoin('cat_opciones', 'cat_opciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.cat_opciones_id')
        ->whereIn('fus_configuracion_autorizaciones.id', $ids)
        ->where('fus_configuracion_autorizaciones.estado', '=', 1)
        ->groupby('fus_configuracion_autorizaciones.rol_mod_rep')
        ->get()
        ->toArray();

        $resultIds = '';
        
        foreach($query AS $rows) {
            switch ($rows['applications_id']) {
                case 20:
                case 16:
                case 64:                   
                case 1032:
                    if(!empty($rows['cat_opciones_id'])) {
                        $subQueryWhere = '(cat_opciones.cat_opciones_id = '.$rows['cat_opciones_id'].' OR cat_opciones.id = '.$rows['idCatOpt'].')';
                    } else {
                        $subQueryWhere = 'cat_opciones.id = '.$rows['idCatOpt'];
                    }
                
                    $ids = FusConfiguracionesAutorizaciones::from('fus_configuracion_autorizaciones')
                    ->select(
                        DB::raw('GROUP_CONCAT(DISTINCT fus_configuracion_autorizaciones.id) AS ids')
                    )
                    ->join('rel_catopciones_configuracionesautorizaciones', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id', '=', 'fus_configuracion_autorizaciones.id')
                    ->join('cat_opciones', 'cat_opciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.cat_opciones_id')
                    ->where('fus_configuracion_autorizaciones.rol_mod_rep', '=', $rows['rol_mod_rep'])
                    ->where('fus_configuracion_autorizaciones.applications_id', '=', $rows['applications_id'])
                    ->whereRaw($subQueryWhere)
                    ->get()
                    ->toArray();
                    
                    break;
                
                default:
                    if(isset($rows["idCatOpt"]) && $rows["idCatOpt"] != null) {
                        if(!empty($rows['cat_opciones_id'])) {
                            $subQueryWhere = '(cat_opciones.cat_opciones_id = '.$rows['cat_opciones_id'].' OR cat_opciones.id = '.$rows['idCatOpt'].')';
                        } else {
                            $subQueryWhere = 'cat_opciones.id = '.$rows['idCatOpt'];
                        }

                        $ids = FusConfiguracionesAutorizaciones::select(
                            DB::raw('GROUP_CONCAT(fus_configuracion_autorizaciones.id) AS ids')
                        )
                        ->join('rel_catopciones_configuracionesautorizaciones', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id', '=', 'fus_configuracion_autorizaciones.id')
                        ->join('cat_opciones', 'cat_opciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.cat_opciones_id')
                        ->where('fus_configuracion_autorizaciones.rol_mod_rep', '=', $rows['rol_mod_rep'])
                        ->where('fus_configuracion_autorizaciones.applications_id', '=', $rows['applications_id'])
                        ->whereRaw($subQueryWhere)
                        ->get()
                        ->toArray();
                        
                    } else {
                        $ids = FusConfiguracionesAutorizaciones::select(
                            DB::raw('GROUP_CONCAT(id) AS ids')
                        )
                        ->where('rol_mod_rep', '=', $rows['rol_mod_rep'])
                        ->where('applications_id', '=', $rows['applications_id'])
                        ->get()
                        ->toArray();
                    }
                    break;
            }

            $resultIds .= $ids[0]['ids'].',';
        }

        if(!empty($ids[0]['ids'])) {
            $resultIds = substr($resultIds, 0, -1);
            $resultIds = explode(',', $resultIds);
        }

        return $resultIds;
    }

    public function getConfiguraciones() {
        return FusConfiguracionesAutorizaciones::select('claveapp_temp')
        ->where('estado', '=', '1')
        ->groupBy('claveapp_temp')
        ->get()
        ->toArray();
    }
    public function getConfiguracionesByIdApp($idapp) {
        return FusConfiguracionesAutorizaciones::where('estado', '=', '1')
        ->where('applications_id', '=', $idapp)
        ->groupBy('rol_mod_rep')
        ->orderby('rol_mod_rep', 'ASC')
        ->get()
        ->toArray();
    }
    public function getAutorizacionesByID($id) {
        return FusConfiguracionesAutorizaciones::find($id);
    }
    public function getConfiguracionAutorizacionesByID($id) {
        $rol_mod_rep = '';

        if(strpos($id, ',') === false) {
            $config = FusConfiguracionesAutorizaciones::select('rol_mod_rep')
            ->find($id);

            $rol_mod_rep = $config['rol_mod_rep'];
        } else {
            $ids = explode(',', $id);
            $config = FusConfiguracionesAutorizaciones::select([
                DB::raw('GROUP_CONCAT(rol_mod_rep) AS rol_mod_rep')
            ])
            ->whereIn('id', $ids)
            ->get()
            ->toArray();

            $rol_mod_rep = $config[0]['rol_mod_rep'];
        }

        return $rol_mod_rep;
    }
    public function getConfiguracionAutorizacionesByIdERPDiscoverer($idEmp, $idConf) {
        $rol_mod_rep = '';
        $config = FusConfiguracionesAutorizaciones::select(['rol_mod_rep', 'applications_id'])
        ->find($idConf);
        
        if($idEmp != 0) {

            switch($config['applications_id']) {
                case 1032:
                    $optCat = Op_cat_model::find($idEmp)->toArray();
                    
                    $rol_mod_rep = $optCat['cat_op_descripcion']." / ".$config['rol_mod_rep'];
                    break;
                default:
                    $optCat = Op_cat_model::find($idEmp)->toArray();
                    $claveEmp = explode(" - ", $optCat['cat_op_descripcion']);
                    
                    $rol_mod_rep = str_replace("%%%", $claveEmp[0], $config['rol_mod_rep']);
                    break;
            }
        } else {
            $rol_mod_rep = $config['rol_mod_rep'];
        }

        return $rol_mod_rep;
    }
    public function getConfiguracionAutorizacionesByIdERPDiscovererFlijoAut($idEmp, $idConf, $idConfCorrecto) {
        $rol_mod_rep = '';
        
        $config = FusConfiguracionesAutorizaciones::select('rol_mod_rep')
        ->find($idConf);
        
        $configCorrecto = FusConfiguracionesAutorizaciones::select('rol_mod_rep')
        ->find($idConfCorrecto);

        if($config['rol_mod_rep'] == $configCorrecto['rol_mod_rep']){
            if($idEmp != 0) {
                $optCat = Op_cat_model::find($idEmp)->toArray();
                $claveEmp = explode(" - ", $optCat['cat_op_descripcion']);
                
                $rol_mod_rep = str_replace("%%%", $claveEmp[0], $configCorrecto['rol_mod_rep']).", ";
            } else {
                $rol_mod_rep = $configCorrecto['rol_mod_rep'].", ";
            }
        }
        
        if(!empty($rol_mod_rep)) {
            return $rol_mod_rep;
        }

        // return $rol_mod_rep;
    }
    
    public function totalbyresponsabilidad($id) {
        $query = FusConfiguracionesAutorizaciones::selectRaw('
            (
                SELECT 
                    COUNT(id) 
                FROM 
                    fus_configuracion_autorizaciones AS fca 
                WHERE 
                    fca.rol_mod_rep = fus_configuracion_autorizaciones.rol_mod_rep
            ) AS total
        ')
        ->where('id', '=', $id)
        ->first();

        if($query != null) {
            return $query->total;
        }
    }

    public function data()
    {
        $controlConfigFuseApp = new ControlConfigFuseApp;
        $appsAdmonMesa = $controlConfigFuseApp->usuarioByNoEmpName(Auth::user()->noEmployee, Auth::user()->name);

        if($appsAdmonMesa["todo"] == 1) {
            $this->apps = null;
        } else {
            $this->apps = json_decode($appsAdmonMesa["apps"], true);
        }

        return FusConfiguracionesAutorizaciones::select([
            'fus_configuracion_autorizaciones.id AS idFusResp',
            'fus_configuracion_autorizaciones.correo', 
            'fus_configuracion_autorizaciones.rol_mod_rep',
            'fus_configuracion_autorizaciones.no_empleado_labora', 
            'fus_configuracion_autorizaciones.nombre_labora', 
            'fus_configuracion_autorizaciones.usuario_red',
            'fus_configuracion_autorizaciones.created_at AS fecha_creacion',
            'applications.alias',
            DB::raw('
                (SELECT path FROM fus_archivo_anexo WHERE id_relacion = fus_configuracion_autorizaciones.id) AS path
            '),
            DB::raw("
                case 
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 0 then 'Aplicacion'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 1 then 'Grupo'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 2 then 'Reporte'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 3 then 'Responsabilidad'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 4 then 'Rol'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 5 then 'Otros'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 6 then 'Perfiles'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 7 then 'Funciones'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 8 then 'Empresa'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 9 then 'Instancia'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 10 then 'Área'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 11 then 'Permiso'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 12 then 'Administración'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 13 then 'Portafolio'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 14 then 'Fondo'
                    when fus_configuracion_autorizaciones.tipo_autorizacion = 15 then 'Perfil SOS'
                end AS autorizacion
            ")
        ])
        ->join('applications', 'applications.id', '=', 'fus_configuracion_autorizaciones.applications_id')
        ->where('fus_configuracion_autorizaciones.estado', '<>',2)
        ->where(function($query) {
            if($this->apps != null || is_array($this->apps) === true){
                $query->whereIn('fus_configuracion_autorizaciones.applications_id', $this->apps);
            } else {
                $query->whereNotNull('fus_configuracion_autorizaciones.applications_id');
            }
        })
        ->get()
        ->toArray();
    }
    public function get_autorizador($desde, $hasta, $tipoaut, $app, $responsabilidad, $user,$estatus)
    {
        $this->desde=$desde;
        $this->hasta=$hasta;
        $this->tipoaut=$tipoaut;
        $this->app=$app;
        $this->responsabilidad=$responsabilidad;
        $this->user=$user;
        $this->estatus=$estatus;

        return FusConfiguracionesAutorizaciones::select([
            'fus_configuracion_autorizaciones.correo', 
            DB::raw("TRIM(REPLACE(REPLACE(REPLACE(TRIM(rol_mod_rep),'\\t',''),'\\n',''),'\\r','')) AS responsabilidad"),
            'fus_configuracion_autorizaciones.nombre_labora AS nombre', 
            'fus_configuracion_autorizaciones.usuario_red',
            DB::raw("if(fus_configuracion_autorizaciones.id_usr_alta is null, 'CARGA INICIAL', user_red) as usr_alta"),
            'fus_configuracion_autorizaciones.created_at AS fecha_alta',
            DB::raw("(select user_red from fus_user_login where id =fus_configuracion_autorizaciones.id_usr_cambio) as usr_baja"),
            'fus_configuracion_autorizaciones.updated_at AS fecha_baja',
            DB::raw('if(fus_configuracion_autorizaciones.applications_id = 20,
                CASE
                    WHEN cat_opciones.id = 40 THEN CONCAT(applications.alias,"- PROD")
                    WHEN cat_opciones.id = 41 THEN CONCAT(applications.alias,"- INTERMEX")
                    WHEN cat_opciones.cat_opciones_id = 40 THEN CONCAT(applications.alias,"- PROD")
                    WHEN cat_opciones.cat_opciones_id = 41 THEN CONCAT(applications.alias,"- INTERMEX")
                END, if(fus_configuracion_autorizaciones.applications_id = 16,                
                CASE                    
                WHEN cat_opciones.id = 738 THEN CONCAT(applications.alias,"- PROD")                    
                WHEN cat_opciones.id = 739 THEN CONCAT(applications.alias,"- INTERMEX")              
                 END,if(fus_configuracion_autorizaciones.applications_id = 64,                
                 CASE                    
                 WHEN cat_opciones.id = 5030 THEN CONCAT(`applications`.`alias`,"- PROD")                    
                 WHEN cat_opciones.id = 5031 THEN CONCAT(`applications`.`alias`,"- INTERMEX")              
                  END, `applications`.`alias`))) 
            AS aplicacion'),
            DB::raw("
                case 
                    when fus_configuracion_autorizaciones.estado = 1 then 'Si'
                    when fus_configuracion_autorizaciones.estado = 2 then 'No'
                end AS estatus"),
            DB::raw("
                case 
                when fus_configuracion_autorizaciones.tipo_autorizador = 1 then 'Mesa'
                when fus_configuracion_autorizaciones.tipo_autorizador = 2 then 'Autorizador'
                when fus_configuracion_autorizaciones.tipo_autorizador = 3 then 'Ratificador'
                end AS tipo_aut
                ")
        ])
        ->join('applications', 'applications.id', '=', 'fus_configuracion_autorizaciones.applications_id')
        ->leftJoin('rel_catopciones_configuracionesautorizaciones', 'fus_configuracion_autorizaciones.id','=', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id')
        ->leftJoin('cat_opciones', 'rel_catopciones_configuracionesautorizaciones.cat_opciones_id', '=', 'cat_opciones.id')
        ->leftJoin('fus_user_login', 'fus_configuracion_autorizaciones.id_usr_alta' ,'=','fus_user_login.id')
        ->where(function($query) {
            if($this->app != null){
                $query->where('fus_configuracion_autorizaciones.applications_id', '=',$this->app);
            } else {
                $query->whereNotNull('fus_configuracion_autorizaciones.applications_id');
            }
            if($this->desde != null){
                if($this->hasta != null){
                    $query->whereBetween('fus_configuracion_autorizaciones.created_at', array($this->desde.' 00:00:00', $this->hasta.' 23:59:59'));
                } else {
                    $query->whereBetween('fus_configuracion_autorizaciones.created_at', array($this->desde.' 00:00:00',  date('Y-m-d G:i:s')));
                }
            } else {
                $query->whereNotNull('fus_configuracion_autorizaciones.created_at');
            }
            if($this->tipoaut != null){
                $query->where('fus_configuracion_autorizaciones.tipo_autorizador', $this->tipoaut);
            } else {
                $query->whereNotNull('fus_configuracion_autorizaciones.tipo_autorizador');
            }
            if($this->responsabilidad != null){
                $query->whereRaw("fus_configuracion_autorizaciones.rol_mod_rep like '".$this->responsabilidad."'");
            } else {
                $query->whereNotNull('fus_configuracion_autorizaciones.rol_mod_rep');
            }
            if($this->user != null){
                $query->whereRaw("upper(fus_configuracion_autorizaciones.nombre_labora) like upper('%".$this->user."%')");
            } else {
                $query->whereNotNull("fus_configuracion_autorizaciones.nombre_labora");
            }
            if($this->estatus != null){
                $query->where('fus_configuracion_autorizaciones.estado', '=', $this->estatus);
            } else {
                $query->whereNotNull('fus_configuracion_autorizaciones.estado');
            }
        })
        ->groupBy('aplicacion')
        ->groupBy('nombre')
        ->groupBy('responsabilidad')
        ->orderBy('fus_configuracion_autorizaciones.rol_mod_rep','ASC')
        ->get()
        ->toArray();
    }
    public function baja_logica($val,$usrid)
    {
        date_default_timezone_set('America/Monterrey');
        $data = FusConfiguracionesAutorizaciones::find($val);
        $data->id_usr_cambio = $usrid;
        $data->estado = 2;
        $data->updated_at = date('Y-m-d G:i:s');
        $data->save();
    }

    public function totalMCAURATRequeridos($idFus, $tipo, $idApp) {
        return FusConfiguracionesAutorizaciones::join('rel_configuration_fussyswtl', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id', '=', 'fus_configuracion_autorizaciones.id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $idFus)
        ->where('fus_configuracion_autorizaciones.tipo_autorizador', '=', $tipo)
        ->where('fus_configuracion_autorizaciones.applications_id', '=', $idApp)
        ->where('fus_configuracion_autorizaciones.estado', '=', 1)
        ->groupby('rol_mod_rep')
        ->count();
    }

    public function validarAutorizacionORechazo($data) {
        return FusConfiguracionesAutorizaciones::selectRaw('
            (
                IF(
                    (
                        SELECT 
                            COUNT(*)
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN 
                            rel_configuration_fussyswtl 
                        ON 
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = '.$data['idFus'].'
                        AND
                            fus_configuracion_autorizaciones.applications_id = '.$data['idApp'].'
                        AND
                            rel_configuration_fussyswtl.estado_autorizacion = 1
                        AND
                            fus_configuracion_autorizaciones.rol_mod_rep = "'.$data['nombreObjeto'].'"
                        AND
                            fus_configuracion_autorizaciones.tipo_autorizador = "'.$data['tipo_autorizador'].'"
                    ) > 0,
                    1,
                    0
                ) 
            ) AS autorizaciones,
            (
                IF(
                    (
                        SELECT 
                            COUNT(*)
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN 
                            rel_configuration_fussyswtl 
                        ON 
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = '.$data['idFus'].'
                        AND
                            fus_configuracion_autorizaciones.applications_id = '.$data['idApp'].'
                        AND
                            rel_configuration_fussyswtl.estado_autorizacion = 2
                        AND
                            fus_configuracion_autorizaciones.rol_mod_rep = "'.$data['nombreObjeto'].'"
                        AND
                            fus_configuracion_autorizaciones.tipo_autorizador = "'.$data['tipo_autorizador'].'"
                    ) > 0,
                    1,
                    0
                ) 
            ) AS rechazos
        ')
        ->join('rel_configuration_fussyswtl', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id', '=', 'fus_configuracion_autorizaciones.id')
        ->whereRaw('
                rel_configuration_fussyswtl.fus_sysadmin_wtl_id = '.$data['idFus'].'
            AND
                fus_configuracion_autorizaciones.applications_id = '.$data['idApp'].'
            AND
                fus_configuracion_autorizaciones.rol_mod_rep = "'.$data['nombreObjeto'].'"
            AND
                fus_configuracion_autorizaciones.estado = 1
            AND
                fus_configuracion_autorizaciones.tipo_autorizador = "'.$data['tipo_autorizador'].'"
        ')
        ->groupby('fus_configuracion_autorizaciones.rol_mod_rep')
        ->first()
        ->toArray();
    }
    public function autocomplete($val, $idapp){
        return FusConfiguracionesAutorizaciones::select('rol_mod_rep')
        ->whereRaw("UPPER(rol_mod_rep) LIKE UPPER('".$val."%')")
        ->where("applications_id", "=", $idapp)
        ->groupby('rol_mod_rep')
        ->limit(5)
        ->get()
        ->toArray();
    }

    public function getAutorizacionesByIdappAndIdsAutsSeleccionadas($idAuts, $idApp) {
        $getAuts = FusConfiguracionesAutorizaciones::select('rol_mod_rep')
        ->whereIn('id', $idAuts)
        ->get()
        ->toArray();
        
        $nombresAutorizaciones = array();
        
        foreach($getAuts AS $key => $row) {
            $nombresAutorizaciones[] = $row["rol_mod_rep"];
        }
        
        $todaslasAuts = FusConfiguracionesAutorizaciones::select(['id', 'rol_mod_rep'])
        ->whereIn('rol_mod_rep', $nombresAutorizaciones)
        ->where('applications_id', '=', $idApp)
        ->get()
        ->toArray();
        
        return $todaslasAuts;   
    }
}
