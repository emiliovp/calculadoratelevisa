<?php

namespace App;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\FusConfiguracionesAutorizaciones;
use App\EmpresaFilial;
use App\ActiveDirectoriActive;
use App\ObservacionPorRechazo;
use App\FusConfigAutOtro;
use App\RelOtrosAutorizaciones;
use App\Applications;
use App\RelConfigurationfussyswtl;
use App\Op_cat_model;
use App\Http\Controllers;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\FusSysadminController;

class SolicitudModelo extends Model
{
    protected $table = "cal_solicitud";

    protected $fillable = [
        'id',
        'cal_nombre',
        'cal_a_paterno',
        'cal_a_materno',
        'cal_puesto',
        'cal_ubicacion',
        'cal_telefono',
        'cal_tel_ext',
        'cal_departamento',
        'cal_area',
        'cal_centro_costos',
        'cal_no_empleado',
        'cal_no_gafete',
        'cal_correo',
        'cal_usuario_red',
        'cal_desde',
        'cal_hasta',
        'cal_tipo_movimiento',
        'cal_fus_cuerpo',
        'cal_empresa_filial_id',
        'cal_estado_fus',
        'cal_tipo_fus',
        'cal_vigencia',
        'cal_no_empleado_jefe',
        'cal_correo_jefe',
        'cal_puesto_jefe',
        'cal_nombre_jefe',
        'cal_apat_jefe',
        'cal_amat_jefe',
        'cal_autorizo_jefe',
        'cal_fecha_auto_jefe',
        'cal_no_empleado_aut',
        'cal_aut_correo',
        'cal_aut_puesto',
        'cal_aut_nombre',
        'cal_aut_apat',
        'cal_aut_amat',
        'cal_aut_autorizo',
        'cal_fecha_auto_autorizador',
        'cal_ext_ficha',
        'cal_ext_empresa',
        'cal_ext_nombre',
        'cal_ext_apat',
        'cal_ext_amat',
        'cal_ext_ubicacion',
        'cal_ext_proyecto',
        'cal_ext_vigencia',
        'cal_clave_atencion',
        'cal_fecha_atencion',
        'cal_catalogo_equipo_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function create_sol($data)
    {
        /*$fusConfi = new FusConfigAutOtro;
        $autorizadores = $fusConfi->getCorreosAutorizadores($data['tipo_fus']); */ 
        $a = SolicitudModelo::create($data);

        /*foreach($autorizadores AS $keys => $values) {
            $crearRelacion = new RelOtrosAutorizaciones;
            $crearRelacion->fus_sysadmin_wtl_id = $a->id;
            $crearRelacion->conf_aut_otros_id = $values['id'];
            $crearRelacion->save();
        }*/

        return $a;
    }

    public function recuperar_info($number)
    {
       $a = SolicitudModelo::select(['id as folio_fus',
            DB::raw("case when tipo_fus = 0 THEN 'FUS de aplicaciones'
            when tipo_fus = 1 THEN 'FUS de solicitud de usuario de red '
            when tipo_fus = 2 THEN 'FUS de solicitud de correo'
            when tipo_fus = 3 THEN 'FUS de solicitud de cuenta de correo especial'
            when tipo_fus = 4 THEN 'FUS de solicitud de acceso a una carpeta o directorio'
            when tipo_fus = 5 THEN 'FUS de solicitud de acceso a la red por VPN'
            when tipo_fus = 6 THEN 'FUS de solicitud de acceso a la red corporativa'
            END as tipo_fus"),
            DB::raw("case when estado_fus = 0 THEN 'Pendiente'
            when estado_fus = 1 THEN 'Autorizado'
            when estado_fus = 2 THEN 'Parcialmente Autorizado'
            when estado_fus = 3 THEN 'Rechazado'
            when estado_fus = 4 THEN 'Atendido'
            END as estado"),
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') AS fecha_fus")
        ])
        ->where('no_empleado', '=', $number)
        ->get()
        ->toArray();
        
        return $a;
    }

    public function cambioEstadoFusDependiendoApp($idFus) {
        $query = SolicitudModelo::select([
            DB::raw('
                (
                    SELECT COUNT(id) FROM rel_fussyswtl_app rfa WHERE rfa.fus_sysadmin_wtl_id = fus_sysadmin_wtl.id AND estado_app = 2
                ) AS autorizados,
                (
                    SELECT COUNT(id) FROM rel_fussyswtl_app rfa WHERE rfa.fus_sysadmin_wtl_id = fus_sysadmin_wtl.id AND estado_app = 0
                ) AS pendientes,
                (
                    SELECT COUNT(id) FROM rel_fussyswtl_app rfa WHERE rfa.fus_sysadmin_wtl_id = fus_sysadmin_wtl.id AND estado_app = 1
                ) AS rechazados
            ')
        ])
        ->where('fus_sysadmin_wtl.id', '=', $idFus)
        ->get()
        ->toArray();
        // dd($idFus, $query);
        if(
            $query[0]['autorizados'] == 0 &&
            $query[0]['pendientes'] == 0 &&
            $query[0]['rechazados'] > 0
        ) {
            $this->cambioEstadoFusApp($idFus, 3);
        } else if(
            $query[0]['autorizados'] > 0 &&
            $query[0]['pendientes'] == 0 &&
            $query[0]['rechazados'] == 0
        ){
            $this->cambioEstadoFusApp($idFus, 1);
        }
    }

    public function listaFusPorAutorizar($noEmp) {
        $query = SolicitudModelo::select([
            'fus_sysadmin_wtl.id AS folio_fus',
            DB::raw("CASE 
                WHEN fus_sysadmin_wtl.tipo_fus = 0 THEN 'FUS de aplicaciones'
                WHEN fus_sysadmin_wtl.tipo_fus = 1 THEN 'FUS de solicitud de usuario de red'
                WHEN fus_sysadmin_wtl.tipo_fus = 2 THEN 'FUS de solicitud de correo'
                WHEN fus_sysadmin_wtl.tipo_fus = 3 THEN 'FUS de solicitud de cuenta de correo especial'
                WHEN fus_sysadmin_wtl.tipo_fus = 4 THEN 'FUS de solicitud de acceso a una carpeta o directorio'
                WHEN fus_sysadmin_wtl.tipo_fus = 5 THEN 'FUS de solicitud de acceso a la red por VPN'
                WHEN fus_sysadmin_wtl.tipo_fus = 6 THEN 'FUS de solicitud de acceso a la red corporativa'
            END as tipo_fus"),
            DB::raw("case when estado_fus = 0 THEN 'Pendiente'
            when estado_fus = 1 THEN 'Autorizado'
            when estado_fus = 2 THEN 'Parcialmente Autorizado'
            when estado_fus = 3 THEN 'Rechazado'
            when estado_fus = 4 THEN 'Atendido'
            END as estadofus"),
            'fus_sysadmin_wtl.created_at AS fecha_fus'
        ])
        ->whereRaw('
                id IN (SELECT rel_configuration_fussyswtl.fus_sysadmin_wtl_id FROM rel_configuration_fussyswtl INNER JOIN fus_configuracion_autorizaciones ON fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id WHERE fus_configuracion_autorizaciones.no_empleado_labora = '.$noEmp.')
            AND
                autorizo_jefe = 1
            AND
                no_empleado_aut IS NOT NULL
            AND
                aut_autorizo = 1
            OR
                id IN (SELECT rel_configuration_fussyswtl.fus_sysadmin_wtl_id FROM rel_configuration_fussyswtl INNER JOIN fus_configuracion_autorizaciones ON fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id WHERE fus_configuracion_autorizaciones.no_empleado_labora = '.$noEmp.')
            AND
                autorizo_jefe = 1
            AND
                no_empleado_aut IS NULL
        ')
        ->get()
        ->toArray();
        
        return $query;
    }

    public function recuperar_area($cve_atencion)
    {
       $a= SolicitudModelo::from('fus_sysadmin_wtl')
        ->select([
            'id as folio_fus','id as folio_fus',
            'clave_atencion',
            DB::raw("
            (
                SELECT 
                    GROUP_CONCAT(alias) 
                FROM 
                    applications 
                INNER JOIN
                    rel_fussyswtl_app
                ON
                    rel_fussyswtl_app.applications_id = applications.id
                WHERE
                    rel_fussyswtl_app.fus_sysadmin_wtl_id = fus_sysadmin_wtl.id
            ) AS apps
            "),
            DB::raw("case when tipo_fus = 0 THEN 'FUS de aplicaciones'
            when tipo_fus = 1 THEN 'FUS de solicitud de usuario de red'
            when tipo_fus = 2 THEN 'FUS de solicitud de correo'
            when tipo_fus = 3 THEN 'FUS de solicitud de cuenta de correo especial'
            when tipo_fus = 4 THEN 'FUS de solicitud de acceso a una carpeta o directorio'
            when tipo_fus = 5 THEN 'FUS de solicitud de acceso a la red por VPN'
            when tipo_fus = 6 THEN 'FUS de solicitud de acceso a la red corporativa'
            END as tipo_fus"),
            DB::raw("case when estado_fus = 0 THEN 'Pendiente'
            when estado_fus = 1 THEN 'Autorizado'
            when estado_fus = 2 THEN 'Parcialmente Autorizado'
            when estado_fus = 3 THEN 'Rechazado'
            when estado_fus = 4 THEN 'Atendido'
            END as estado"),
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i:%s') AS fecha_fus")
        ])
        ->where('clave_atencion','=', $cve_atencion)
        ->where('estado_fus', '=', 1)
        ->orWhere('estado_fus', '=', 2)
        ->where('clave_atencion','=', $cve_atencion)
        ->orWhere('estado_fus', '=', 4)
        ->where('clave_atencion','=', $cve_atencion)
        ->get()
        ->toArray();
        
        return $a;
    }

    public function getSolById($id) {
        return SolicitudModelo::find($id)->toArray();
    }
    public function getFusByIdWtl($id) {
        return SolicitudModelo::where('estado_fus', '=', 1)
        ->whereIn('tipo_fus', [1,2,3])
        ->where('id', '=', $id)
        ->first();
    }
    public function getFusByIdWtlReenvio($id) {
        return SolicitudModelo::where('id', '=', $id)
        ->whereIn('estado_fus', [1,4])
        ->whereIn('tipo_fus', [1,2,3])
        ->first();
    }
    public function update_fus($id,$data)
    {
        SolicitudModelo::find($id)->update(['fus_cuerpo' => json_encode($data, JSON_PRETTY_PRINT)]); // update device 1
    }

    public function trackAutorizaciones($id) {

    }

    public function getFusInfProcesada($id) {
        $this->trackAutorizaciones($id);
        $app = new Applications;
        $emp = new EmpresaFilial;
        $FusSysadminController = new FusSysadminController;
        $configs = new FusConfiguracionesAutorizaciones;

        $fus = SolicitudModelo::find($id)->toArray();
        $fus['empresa_nombre'] = $emp->getEmpresa($fus['empresa_filial_id']);
        if ($fus['ext_empresa'] != NULL) {
            $fus['ext_empresa'] = $emp->getEmpresa($fus['ext_empresa']);
        }
        
        if($fus['tipo_fus'] == 0) {
            $fusJSON = json_decode($fus['fus_cuerpo'], true);
            $fusProcesado = array();
            if($fusJSON != null) {
                foreach($fusJSON AS $keys => $values) {
                    foreach($values AS $key => $value) {
                        if(isset($value['tipo']) && $value['tipo'] == 1) {
                            
                            $conservandoValor = $value['valor'];
                            switch ($keys) {
                                case 20:
                                case 1032:
                                    $formateada = explode(',', $value['valor']);
                                    
                                    $idConf = '';
                                    $idEmp = '';
                                    $count = 1;
                                    $totalIds = count($formateada);
                                    $ResponsabilidadesProcesadas = '';
                                    
                                    if(!empty($value['valor'])) {
                                        foreach($formateada AS $index => $val) {
                                            $temp = explode('-', $val);
                                            if($count == $totalIds) {
                                                $ResponsabilidadesProcesadas .= $configs->getConfiguracionAutorizacionesByIdERPDiscoverer($temp[0], $temp[1]);
                                            } else {
                                                $ResponsabilidadesProcesadas .= $configs->getConfiguracionAutorizacionesByIdERPDiscoverer($temp[0], $temp[1]).", ";
                                            }
                                            $count = $count+1;
                                        }    
                                    }
                                    
                                    $value['valor'] = $ResponsabilidadesProcesadas;
                                    break;
                                default:
                                    $value['valor'] = $configs->getConfiguracionAutorizacionesByID($value['valor']);
                                    break;
                            }
                            
                            if(empty($value['valor'])) {
                                $value['valor'] = $conservandoValor;
                            }
                        } else if(isset($value['tipo']) && $value['tipo'] == 2) {
                            $arrayIds = explode(',', $value['valor']);
                            $cat_opciones = Op_cat_model::select('cat_op_descripcion')
                            ->whereIn('id', $arrayIds)
                            ->get()
                            ->toArray();

                            $nombEmpr = '';
                            foreach($cat_opciones AS $rows) {
                                $nombEmpr .= $rows['cat_op_descripcion'].",";
                            }
                            $value['valor'] = substr($nombEmpr, 0, -1);
                        }
                        
                        $fusProcesado[$FusSysadminController->appsTitulos($keys)][$key] = $value;
                        // $fusProcesado[$app->getNameApplicationById($keys)][$key] = $value;
                    }
                    
                    $urlAnexos = new RelAnexosFus;
                    $anexos = $urlAnexos->getAllsSRCAnexo($keys, $id);
                    
                    switch (count($anexos)) {
                        case 1:
                            $fusProcesado[$FusSysadminController->appsTitulos($keys)]['path_anexos'] = $anexos[0]['path'];
                            // $fusProcesado[$app->getNameApplicationById($keys)]['path_anexos'] = $anexos[0]['path'];
                            break;
                        case 0:
                            break;
                        default:
                            $fusProcesado[$FusSysadminController->appsTitulos($keys)]['path_anexos'] = $anexos;
                            // $fusProcesado[$app->getNameApplicationById($keys)]['path_anexos'] = $anexos;
                            break;
                    }
                }
                $fus['fus_cuerpo'] = $fusProcesado;
            } else {
                $fus['fus_cuerpo'] = null;
            }
        } else {
            $fus['fus_cuerpo'] = json_decode($fus['fus_cuerpo'], true);
        }
        $getNumbersEmployeesAutsApps = new RelConfigurationfussyswtl;
        $fus['no_empleados_auths_apps'] = $getNumbersEmployeesAutsApps->getNumbersEmployeesByIdFus($id);
        $getNumbersEmployeesAutsOtros = new RelOtrosAutorizaciones;
        $fus['no_empleados_auths_otros'] = $getNumbersEmployeesAutsOtros->getNumbersEmployeesByIdFus($id);

        return $fus;
    }

    public function disicionfusjefe($id, $jefeOAut, $tipoAccion, $observaciones = null, $idRelConf = null) {
        /**
         * $jefeOAut
         * 1 - Jefe
         * 2 - Autorizador
         * 
         * $tipoAccion
         * 1 - Autorización
         * 2 - Rechazo
         * 
         * $idRelConf
         * Si existe es por la autorización o rechazo de apps u otros
         * 
         * $fus->tipo_fus
         * 0 - Aplicaciones (Sysadmin)
         * 1 - Usuarios de Red (Wintel)
         * 2 - Correo (Wintel)
         * 3 - Cuenta de correo especial (Wintel)
         * 4 - Acceso a un directorio o carpeta (Seguridad)
         * 5 - Solicitud de acceso a VPN (Seguridad)
         * 6 - Autorización de acceso a la red corporativa (sutis)
         */
        
        $fus = SolicitudModelo::find($id);
        switch($jefeOAut) {
            case 1:
                switch($tipoAccion) {
                    case 1:
                        $fus->autorizo_jefe = 1;
                        $fus->fecha_auto_jefe =  DB::raw('now()');
                        if($fus->no_empleado_aut != "") {
                            if($fus->aut_autorizo == 1) {
                                switch ($fus->tipo_fus) {
                                    case 0:
                                    case 4:
                                    case 5:
                                    case 6:
                                        $fus->estado_fus = 2;
                                        $fus->fecha_atencion = DB::raw('now()');
                                        break;
                                    
                                    case 1:
                                    case 2:
                                    case 3:
                                        $fus->estado_fus = 1;
                                        $fus->fecha_atencion = DB::raw('now()');
                                        break;
                                }
                            }
                        } else {
                            switch ($fus->tipo_fus) {
                                case 0:
                                case 4:
                                case 5:
                                case 6:
                                    $fus->estado_fus = 2;
                                    $fus->fecha_atencion = DB::raw('now()');
                                    break;
                                
                                case 1:
                                case 2:
                                case 3:
                                    $fus->estado_fus = 1;
                                    $fus->fecha_atencion = DB::raw('now()');
                                    break;
                            }
                        }

                        break;
                    case 2:
                        $fus->autorizo_jefe = 2;
                        $fus->fecha_auto_jefe =  DB::raw('now()');
                        $idObse = "";
                        if($fus->estado_fus != 3) {
                            $fus->estado_fus = 3;
                            $fus->fecha_atencion = DB::raw('now()');
                            $observacion = new ObservacionPorRechazo;
                            $idObse = $observacion->insertMotivoRechazo($id, 0, $observaciones);
                        }
                        break;
                }
                break;
            case 2:
                switch($tipoAccion) {
                    case 1:
                        $fus->aut_autorizo = 1;
                        $fus->fecha_auto_autorizador =  DB::raw('now()');
                        if($fus->autorizo_jefe == 1) {
                            switch ($fus->tipo_fus) {
                                case 0:
                                case 4:
                                case 5:
                                case 6:
                                    $fus->estado_fus = 2;
                                    $fus->fecha_atencion = DB::raw('now()');
                                    break;
                                
                                case 1:
                                case 2:
                                case 3:
                                    $fus->estado_fus = 1;
                                    $fus->fecha_atencion = DB::raw('now()');
                                    break;
                            }
                        }
                        break;
                    case 2:
                        $fus->aut_autorizo = 2;
                        $fus->fecha_auto_autorizador =  DB::raw('now()');
                        $idObse = "";
                        if($fus->estado_fus != 3) {
                            $fus->estado_fus = 3;
                            $fus->fecha_atencion = DB::raw('now()');
                            $observacion = new ObservacionPorRechazo;
                            $idObse = $observacion->insertMotivoRechazo($id, 1, $observaciones);
                        }
                        break;
                }
            break;
            case 3:
                $relConfigAutoApps = new RelConfigurationfussyswtl;
                $infoRelConfugAutApps = $relConfigAutoApps->getRelConfigAutoById($idRelConf);
                
                $infoConfiguraciones = new FusConfiguracionesAutorizaciones;
                $idApp = $infoConfiguraciones->getAutorizacionesByID($infoRelConfugAutApps->fus_configuracion_autorizaciones_id);
                
                $RelFusApps = new RelFusApps;
                $estadoApp = $RelFusApps->getEstaAppFus($id, $idApp['applications_id']);
                $nuevoEstadoApp = 0;
                switch($tipoAccion) {
                    case 1:
                        $infoRelConfugAutApps->estado_autorizacion = 1;
                        $infoRelConfugAutApps->fecha_atencion =  DB::raw('now()');
                        $infoRelConfugAutApps->save();

                        $classFus = new SolicitudModelo;
                        $nuevoEstadoApp = $classFus->changeStatusApp($id, $idApp['applications_id'], $infoRelConfugAutApps->fus_configuracion_autorizaciones_id);         
                        
                        if($estadoApp['estado_app'] == 0 || $estadoApp['estado_app'] != 2) {
                            $RelFusApps->atendidoApp($idApp['applications_id'], $id, $nuevoEstadoApp);
                        }
                        
                        if($relConfigAutoApps->getRelConfigAutoByIdFus($id) == 1) {
                            $fus->estado_fus = 1;
                            $fus->fecha_atencion = DB::raw('now()');
                        }
                        
                        break;
                    case 2:
                        $infoRelConfugAutApps->estado_autorizacion = 2;
                        $infoRelConfugAutApps->fecha_atencion =  DB::raw('now()');
                        $observacion = new ObservacionPorRechazo;
                        $idObse = $observacion->insertMotivoRechazo($id, 2, $observaciones, $idRelConf);
                        $infoRelConfugAutApps->save();

                        if($estadoApp['estado_app'] == 0) {
                            $RelFusApps->atendidoApp($idApp['applications_id'], $id, 1);
                        }
                        break;
                }
                
                break;
            case 4:
                $relConfigAuto = new RelOtrosAutorizaciones;
                $infoRelConfugAut = $relConfigAuto->getRelConfigAutoById($idRelConf);

                switch($tipoAccion) {
                    case 1:
                        $infoRelConfugAut->estado = 1;
                        $infoRelConfugAut->save();
                        
                        if($relConfigAuto->getRelConfigAutoByIdFus($id) == 1) {
                            $fus->estado_fus = 1;
                            $fus->fecha_atencion = DB::raw('now()');
                        }
                        
                        break;
                    case 2:
                        $infoRelConfugAut->estado = 2;
                        $fus->estado_fus = 3;
                        $fus->fecha_atencion = DB::raw('now()');
                        $observacion = new ObservacionPorRechazo;
                        $idObse = $observacion->insertMotivoRechazo($id, 3, $observaciones, $idRelConf);
                        $infoRelConfugAut->save();
                        break;
                }
                
                break;
            // Casos para las atenciones
            case 5:
            case 6:
                if ($tipoAccion == 1) {
                    $notConfir = new NotificacionesController;
                     $notConfir->fusAutorizado($id);
                }
            case 7:
            case 8:
                switch($tipoAccion) {
                    case 1:
                        $fus->estado_fus = 4;
                        $fus->fecha_atencion = DB::raw('now()');
                        break;
                    case 2:
                        $fus->estado_fus = 3;
                        $fus->fecha_atencion = DB::raw('now()');
                        $observacion = new ObservacionPorRechazo;
                        $idObse = $observacion->insertMotivoRechazo($id, 3, $observaciones);
                        break;
                }
                
                break;
        }

        $fus->save();

        if(isset($idObse)) {
            return $idObse;
        }
    }

    public function changeStatusApp($idfus, $idapp, $fusConfiguracionAutorizacionesId) {
        $relConfig = RelConfigurationfussyswtl::select('rol_mod_rep')
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('fus_sysadmin_wtl_id', '=', $idfus)
        ->where('applications_id', '=', $idapp)
        ->groupby('rol_mod_rep')
        ->get()
        ->toArray();

        $final = array();
        $suma = 0;
        foreach($relConfig AS $rows) {
            $query = SolicitudModelo::from('fus_sysadmin_wtl AS fsw')
            ->select([
                DB::raw('
                    (
                        SELECT 
                            COUNT(fus_configuracion_autorizaciones.id) 
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN
                            rel_configuration_fussyswtl
                        ON
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = fsw.id
                        AND 
                            tipo_autorizador = 1
                        AND
                            rol_mod_rep = "'.$rows['rol_mod_rep'].'"
                        AND
                            applications_id = '.$idapp.'
                    ) AS mc
                '),
                DB::raw('
                    (
                        SELECT 
                            COUNT(fus_configuracion_autorizaciones.id) 
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN
                            rel_configuration_fussyswtl
                        ON
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = fsw.id
                        AND 
                            tipo_autorizador = 1
                        AND
                            estado_autorizacion = 1
                        AND
                            rol_mod_rep = "'.$rows['rol_mod_rep'].'"
                        AND
                            applications_id = '.$idapp.'
                    ) AS mc_estado
                '),
                DB::raw('
                    (
                        SELECT 
                            COUNT(fus_configuracion_autorizaciones.id) 
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN
                            rel_configuration_fussyswtl
                        ON
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = fsw.id
                        AND 
                            tipo_autorizador = 2
                        AND
                            rol_mod_rep = "'.$rows['rol_mod_rep'].'"
                        AND
                            applications_id = '.$idapp.'
                    ) AS autorizador
                '),
                DB::raw('
                    (
                        SELECT 
                            COUNT(fus_configuracion_autorizaciones.id) 
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN
                            rel_configuration_fussyswtl
                        ON
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = fsw.id
                        AND 
                            tipo_autorizador = 2
                        AND
                            estado_autorizacion = 1
                        AND
                            rol_mod_rep = "'.$rows['rol_mod_rep'].'"
                        AND
                            applications_id = '.$idapp.'
                    ) AS autorizador_estado
                '),
                DB::raw('
                    (
                        SELECT 
                            COUNT(fus_configuracion_autorizaciones.id) 
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN
                            rel_configuration_fussyswtl
                        ON
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = fsw.id
                        AND 
                            tipo_autorizador = 3
                        AND 
                            rol_mod_rep = "'.$rows['rol_mod_rep'].'"
                        AND
                            applications_id = '.$idapp.'
                    ) AS ratificador
                '),
                DB::raw('
                    (
                        SELECT 
                            COUNT(fus_configuracion_autorizaciones.id) 
                        FROM 
                            fus_configuracion_autorizaciones 
                        INNER JOIN
                            rel_configuration_fussyswtl
                        ON
                            rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id
                        WHERE 
                            rel_configuration_fussyswtl.fus_sysadmin_wtl_id = fsw.id
                        AND 
                            tipo_autorizador = 3
                        AND
                            estado_autorizacion = 1
                        AND
                            rol_mod_rep = "'.$rows['rol_mod_rep'].'"
                        AND
                            applications_id = '.$idapp.'
                    ) AS ratificador_estado
                ')
            ])
            ->where('id', '=', $idfus)
            ->first()
            ->toArray();
    
            $estadoByTipo = 0; // 0 = Aún pendiente / 1 = Listo
            $relConfigAutoApps = new RelConfigurationfussyswtl;
            $infoCompletaAutorizador = $relConfigAutoApps->getInfoCompletaByIdRel($idfus, $fusConfiguracionAutorizacionesId);

            switch ($infoCompletaAutorizador->tipo_autorizador) {
                case 1:
                    if(
                        $query['autorizador'] > 0 ||
                        $query['ratificador'] > 0
                    ) {
                        $estadoByTipo = 0;
                    } else {
                        if($query['mc_estado'] > 0) {
                            $estadoByTipo = 1;
                        } else {
                            $estadoByTipo = 0;
                        }
                    }
                    break;
                case 2:
                    if($query['ratificador'] > 0) {
                        $estadoByTipo = 0;
                    } else {
                        if($query['autorizador_estado'] > 0) {
                            $estadoByTipo = 1;
                        } else {
                            $estadoByTipo = 0;
                        }
                    }
                    break;
                case 3:
                    if($query['ratificador_estado'] > 0) {
                        $estadoByTipo = 1;
                    } else {
                        $estadoByTipo = 0;
                    }
                    break;
            }
            $final[$rows['rol_mod_rep']] = $estadoByTipo;
            $suma = $suma+$estadoByTipo;
        }
        
        if(count($final) == $suma) {
            return 2;
        }
        return 4;
    }
    // public function exportExcel(){
    public function exportExcel($id){
        $fus = SolicitudModelo::select([  
        DB::raw("if(ext_ficha IS NULL , fus_sysadmin_wtl.nombre , ext_nombre) as firstname"),
        DB::raw('if(ext_ficha IS NULL , concat(a_paterno," ", a_materno) , concat(ext_apat," ", ext_amat)) as lastname'),
        DB::raw('if(ext_ficha IS NULL , concat(a_paterno, " ",a_materno, " " ,fus_sysadmin_wtl.nombre), concat(ext_apat, " ",ext_amat, " " ,ext_nombre)) as DisplayName'),
        DB::raw('"UPN" AS UserPrincipalName'),
        DB::raw('"SFE" AS extensionAttribute1'),
        DB::raw("SUBSTRING_INDEX( centro_costos,  '/' , -1) AS extensionAttribute4"),
        DB::raw('if(ext_ficha IS NULL , "I", "E") AS extensionAttribute10'),
        DB::raw('"U" AS extensionAttribute11'),
        DB::raw("if(ext_ficha IS NULL , no_empleado , ext_ficha) as extensionAttribute15"),
        DB::raw("if(ext_ficha IS NULL , no_empleado , ext_ficha) as EmployeeID"),
        DB::raw("concat(fus_sysadmin_wtl.id,' ',sysdate()) as DescriptionF"),
        DB::raw("if(ext_ficha IS NULL , ubicacion_edificio , ext_ubicacion) as Office"),
        DB::raw("if(ext_ficha IS NULL , tel_ext, NULL) AS telephoneNumber"),
        DB::raw("'1' AS Country"),
        DB::raw("if(ext_ficha IS NULL , puesto, NULL) AS Title"),
        DB::raw("SUBSTRING_INDEX(departamento, '/', 1) AS Department"),
        'empresa_filial.nombre AS Company',
        DB::raw("SUBSTRING_INDEX(departamento, '/', -1) AS DepartmentNumber"),
        DB::raw('"1" AS Manager'),
        DB::raw("concat('01-01-',year(sysdate())+1) as accountExpires"),
        ])
        ->join('empresa_filial', 'empresa_filial.id', '=', DB::raw('if(ext_ficha IS NULL , empresa_filial_id, ext_empresa)'))
        ->distinct()
        ->where('fus_sysadmin_wtl.id','=',$id)
        ->whereIn('fus_sysadmin_wtl.tipo_fus', [1,2,3])
        ->whereIn('fus_sysadmin_wtl.estado_fus', [0,1,2,4,5])
        ->get()
        ->toArray();

        $cuerpo = SolicitudModelo::select([ 
            DB::raw("fus_sysadmin_wtl.puesto_jefe as puesto_jefe"),
            DB::raw("fus_sysadmin_wtl.no_empleado_jefe as no_empleado_jefe"),
            DB::raw("fus_sysadmin_wtl.nombre_jefe as firstnameJefe"),
            DB::raw('concat(apat_jefe," ",amat_jefe ) as lastnameJefe'),

            DB::raw('concat(apat_jefe, " ",amat_jefe, " " ,fus_sysadmin_wtl.nombre_jefe) as DisplayName '),

            DB::raw("if(ext_ficha IS NULL , fus_sysadmin_wtl.nombre , ext_nombre) as firstname"),
            DB::raw('if(ext_ficha IS NULL , a_paterno, ext_apat) as apat'),
            DB::raw('if(ext_ficha IS NULL , a_materno, ext_amat) as amat'),
            DB::raw("case when tipo_fus = 0 THEN 'FUS de aplicaciones'
            when tipo_fus = 1 THEN 'FUS de solicitud de usuario de red'
            when tipo_fus = 2 THEN 'FUS de solicitud de correo'
            when tipo_fus = 3 THEN 'FUS de solicitud de cuenta de correo especial'
            when tipo_fus = 4 THEN 'FUS de solicitud de acceso a una carpeta o directorio'
            when tipo_fus = 5 THEN 'FUS de solicitud de acceso a la red por VPN'
            when tipo_fus = 6 THEN 'FUS de solicitud de acceso a la red corporativa'
            END as tipo_fus"),
            'tipo_fus AS cve_fus',
            'fus_cuerpo'])
        ->where('fus_sysadmin_wtl.id','=',$id)
        ->whereIn('fus_sysadmin_wtl.tipo_fus', [1,2,3])
        ->whereIn('fus_sysadmin_wtl.estado_fus', [0,1,2,4,5])
        ->get()
        ->toArray();
        $upn = $this->get_upn($cuerpo);
        switch ($cuerpo[0]['cve_fus']) {
            case 1:
                $a= json_decode($cuerpo[0]['fus_cuerpo'],true);
                $fus[0]['UserPrincipalName'] = $upn;
                $fus[0]['DescriptionF'] = $a['movimiento']['valor']." ".$fus[0]['DescriptionF'];
                $fus[0]['Dominio'] = $a['dominio']['valor'];
                $fus[0]['SMTP'] = null;
                $fus[0]['cve_fus'] = $cuerpo[0]['cve_fus'];
                break;
            case 2:
                $a= json_decode($cuerpo[0]['fus_cuerpo'],true);
                $fus[0]['UserPrincipalName'] = $upn;
                $fus[0]['DescriptionF'] = $a['movimiento']['valor']." ".$fus[0]['DescriptionF'];
                $fus[0]['Dominio'] = $a['dominio']['valor'];
                $fus[0]['SMTP'] = $a['smtp']['valor'];
                $fus[0]['cve_fus'] = $cuerpo[0]['cve_fus'];
                break;
            case 3: 
                $a= json_decode($cuerpo[0]['fus_cuerpo'],true);
                $fus[0]['DescriptionF'] = $a['movimiento']['valor'].$fus[0]['DescriptionF'];
                $fus[0]['Dominio'] = $a['dominio']['valor'];
                $fus[0]['SMTP'] = $a['smtp']['valor'];
                $fus[0]['cve_fus'] = $cuerpo[0]['cve_fus'];
                $fus[0]['UserPrincipalName'] = $a['n_cuenta']['valor'];
                $fus[0]['firstname'] = $cuerpo[0]['firstnameJefe'];
                $fus[0]['lastname'] = $cuerpo[0]['lastnameJefe'];
                $fus[0]['DisplayName'] = $a['nombre_cuenta']['valor'];
                $fus[0]['Title'] = $cuerpo[0]['puesto_jefe'];
                $fus[0]['EmployeeID'] = $cuerpo[0]['no_empleado_jefe'];
                $fus[0]['extensionAttribute15'] = $cuerpo[0]['no_empleado_jefe'];
                $fus[0]['extensionAttribute10'] = 'E';
                $fus[0]['extensionAttribute11'] = 'U';
                $fus[0]['extensionAttribute4'] = null;
                $fus[0]['Office'] = null;
                $fus[0]['telephoneNumber'] = null;
                $fus[0]['Country'] = null;
                $fus[0]['Department'] = null;
                $fus[0]['Company'] = null;
                $fus[0]['DepartmentNumber'] = null;
                $fus[0]['Manager'] = $cuerpo[0]['firstnameJefe']." ".$cuerpo[0]['lastnameJefe'];
                break;
        } 
        return $fus;
    }
    public function get_upn($data){
        $nombre = $data[0]['firstname'];
        $nombres = explode(' ', $nombre);
        $pri_nombre = preg_replace('/[ <>\'\"]/', '',$nombres[0]);
        if (isset($nombres[1])) {
            $seg_nombre = preg_replace('/[ <>\'\"]/', '',$nombres[1]);
            $seg_nombre = substr($seg_nombre,0,1);
        }
        else {
            $seg_nombre="";
        }
        $a_pat = preg_replace('/[ <>\'\"]/', '',$data[0]['apat']);
        $a_mat = preg_replace('/[ <>\'\"]/', '',$data[0]['amat']);
        $username_ocupado = false;
        $largo_nombre = strlen($a_mat);
        $a = 0;
        $b = 1;
        while ($a <= $largo_nombre) {
            $con = new ActiveDirectoriActive;
            $username_temp_1 = substr($pri_nombre, 0, 1).$seg_nombre.$a_pat; // .
            $username_temp_2 = (isset($a_mat)) ? substr($a_mat, 0, $b) : NULL ;
            $upn =$username_temp_1.$username_temp_2;
            $rest= $con->getEmployeeByUsernameFus($upn);
            if (count($rest) > 0) {
                $a = $largo_nombre + 1;
            }
            else {
                $a = $a + 1;
            }
        }
        return $upn;   
    }
    public function reporteFus(){
        return SolicitudModelo::select('id AS Folio',  
            'created_at AS fecha_creacion',
            'correo_jefe',
            DB::raw('CASE
                WHEN tipo_fus = 1 THEN "Usuarios de Red"
                WHEN tipo_fus = 2 THEN "Correo"
                WHEN tipo_fus = 3 THEN "Cuenta de correo especial"
                END AS tipo_fus'),
            DB::raw('CASE
                WHEN autorizo_jefe = 0 THEN "Pendiente"
                WHEN autorizo_jefe = 1 THEN "Autorizo"
                WHEN autorizo_jefe = 2 THEN "Rechazo"
                END AS EstadoJefe'),
            'fecha_auto_jefe',
            'aut_correo',
            DB::raw('CASE
                WHEN aut_correo = "" THEN ""
                WHEN aut_correo != "" THEN
                    CASE
                        WHEN aut_autorizo = 0 THEN "Pendiente"
                        WHEN aut_autorizo = 1 THEN "Autorizo"
                        WHEN aut_autorizo = 2 THEN "Rechazo"
                    END
                END AS EstadoAutorizador'),
            'fecha_auto_autorizador',
            DB::raw('CASE
                WHEN estado_fus = 0 THEN "Pendiente"
                WHEN estado_fus = 1 THEN "Autorizado"
                WHEN estado_fus = 2 THEN "Parcialmente Autorizado"
                WHEN estado_fus = 3 THEN "Rechazado"
                WHEN estado_fus = 4 THEN "Atendido"
                WHEN estado_fus = 5 THEN "Parcialmente Atendido"
                END AS EstadoFUS'),
            'fecha_atencion'
            )
            ->whereIn('fus_sysadmin_wtl.tipo_fus', [1,2,3])
            ->get()
            ->toArray();
    }

    public function cambioEstadoFusApp($idFus, $estado) {
        SolicitudModelo::find($idFus)->update(['estado_fus' => $estado, 'fecha_atencion' => DB::raw('now()')]);
    }
}