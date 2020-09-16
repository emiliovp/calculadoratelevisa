<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\RelAnexosFus;
use App\ObservacionPorRechazo;
use App\FusConfiguracionesAutorizaciones;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\FusSysadminController;

class RelConfigurationfussyswtl extends Model
{
    protected $table = "rel_configuration_fussyswtl";
    
    protected $fillable = [
        'id',
        'fus_sysadmin_wtl_id', 
        'fus_configuracion_autorizaciones_id', 
        'estado_autorizacion',
        'fecha_atencion',
        'created_at'
    ];

    protected $hidden = [];
    
    protected $primaryKey = 'id';
    
    public $timestamps = false;
    
    public function getNumbersEmployeesByIdFus($idFus) {
        $query = RelConfigurationfussyswtl::select(
            DB::raw('GROUP_CONCAT(DISTINCT fus_configuracion_autorizaciones.no_empleado_labora) AS no_empleados')
        )
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->join('fus_sysadmin_wtl', 'fus_sysadmin_wtl.id', '=', 'rel_configuration_fussyswtl.fus_sysadmin_wtl_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $idFus)
        ->first()
        ->toArray();

        return $query['no_empleados'];
    }

    public function getInfoCompletaByIdRel($idfus, $idrel) {
        return RelConfigurationfussyswtl::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $idfus)
        ->where('rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id', '=', $idrel)
        ->first();
    }

    public function getInfoCompletaById($idfus, $idrel) {
        return RelConfigurationfussyswtl::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $idfus)
        ->where('rel_configuration_fussyswtl.id', '=', $idrel)
        ->first();
    }

    public function getConfiguracionAutorizacionesByIdFUS($id) {
        return RelConfigurationfussyswtl::select(['fus_configuracion_autorizaciones.applications_id', 'fus_configuracion_autorizaciones.correo', 'rel_configuration_fussyswtl.id AS idRelConf'])
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $id)
        ->where('rel_configuration_fussyswtl.estado_autorizacion', '=', '0')
        ->where('fus_configuracion_autorizaciones.estado', '=', '1')
        ->groupby('fus_configuracion_autorizaciones.correo')
        ->get()
        ->toArray();
    }
    
    public function getSetupForTracking($idfus, $idApp, $tipoautorizador) {
        $query = RelConfigurationfussyswtl::from('rel_configuration_fussyswtl AS rcf')
        ->selectRaw('
            (
                select count(*) 
                from rel_configuration_fussyswtl
                inner join fus_configuracion_autorizaciones 
                on fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id 
                where rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                and rel_configuration_fussyswtl.estado_autorizacion = 2
                and fus_configuracion_autorizaciones.applications_id = fca.applications_id
                and fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
            ) AS rechazos,
            rcf.*, 
            fca.*, 
            rcf.id AS idrel
        ')
        ->join('fus_configuracion_autorizaciones AS fca', 'fca.id', '=', 'rcf.fus_configuracion_autorizaciones_id')
        ->where('rcf.fus_sysadmin_wtl_id', '=', $idfus)
        ->where('fca.tipo_autorizador', '=', $tipoautorizador)
        ->where('fca.applications_id', '=', $idApp)
        // ->groupby('fca.correo')
        ->get()
        ->toArray();

        $resultadoFiltrado = array();
        
        foreach($query AS $index => $value) {
            if($value['rechazos'] == 0){
                if(array_search($value['correo'], $resultadoFiltrado) === false) {
                    $resultadoFiltrado[] = $value['correo'];
                }
            }
        }

        return $resultadoFiltrado;
    }

    public function getAppsOfFus($idFUS) {
        $FusSysadminController = new FusSysadminController;
        $apps = RelFusApps::select(
            'applications.alias AS nombre_app',
            'rel_fussyswtl_app.*'
        )
        ->join('applications', 'applications.id', '=', 'rel_fussyswtl_app.applications_id')
        ->where('rel_fussyswtl_app.fus_sysadmin_wtl_id', '=', $idFUS)
        ->groupBy('rel_fussyswtl_app.applications_id')
        ->get()
        ->toArray();

        $estadoActual = '';
        $appsAtualizada = array();
        $relAppsFus = new RelFusApps;

        foreach ($apps as $rows) {
            $calculoEstados = $relAppsFus->getEstaAppFus($rows['fus_sysadmin_wtl_id'], $rows['applications_id']);
            
            switch ($calculoEstados['estado_app']) {
                case 0:
                    $estadoActual = 'Pendiente';
                    break;
                case 1:
                    $estadoActual = 'Rechazado';
                    break;
                case 2:
                    $estadoActual = 'Autorizado';
                    break;
                case 3:
                    $estadoActual = 'Atendido';
                    break;
                case 4:
                    $estadoActual = 'Parcialmente Autorizado';
                    break;
            }
            $detallasFus = array();
            
            $fus = new FUSSysadminWtl;
            $fusProcesado = $fus->getFusInfProcesada($rows['fus_sysadmin_wtl_id']);
            $detallasFus[] = $fusProcesado['fus_cuerpo'][$FusSysadminController->appsTitulos($rows['applications_id'])];

            $urlAnexos = new RelAnexosFus;
            $anexos = $urlAnexos->getAllsSRCAnexo($rows['applications_id'], $rows['fus_sysadmin_wtl_id']);
            $rows['extiste_anexo'] = count($anexos);

            switch (count($anexos)) {
                case 1:
                    $rows['path_anexos'] = $anexos[0]['path'];
                    break;
                case 0:
                    break;
                default:
                    $rows['path_anexos'] = $anexos;
                    break;
            }

            $rows['detalles'] = json_encode($detallasFus);
            $rows['estadoActual'] = $estadoActual;
            array_push($appsAtualizada, $rows);
        }
        
        return $appsAtualizada;
    }

    public function getAppsOfFusPantallaMesasAutorizaciones($idFUS) {
        $FusSysadminController = new FusSysadminController;
        $apps = RelConfigurationfussyswtl::
        select([
            DB::raw('
                *,
                (
                    SELECT alias FROM applications WHERE id = `fus_configuracion_autorizaciones`.`applications_id`
                ) AS nombre_app
            ')
        ])
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $idFUS)
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->groupBy('fus_configuracion_autorizaciones.applications_id')
        ->get()
        ->toArray();

        $estadoActual = '';
        $appsAtualizada = array();
        $relAppsFus = new RelFusApps;

        foreach ($apps as $rows) {
            $calculoEstados = $relAppsFus->getEstaAppFus($rows['fus_sysadmin_wtl_id'], $rows['applications_id']);
            
            switch ($calculoEstados['estado_app']) {
                case 0:
                    $estadoActual = 'Pendiente';
                    break;
                case 1:
                    $estadoActual = 'Rechazado';
                    break;
                case 2:
                    $estadoActual = 'Autorizado';
                    break;
                case 3:
                    $estadoActual = 'Atendido';
                    break;
                case 4:
                    $estadoActual = 'Parcialmente Autorizado';
                    break;
            }
            $detallasFus = array();
            
            $fus = new FUSSysadminWtl;
            $fusProcesado = $fus->getFusInfProcesada($rows['fus_sysadmin_wtl_id']);
            $detallasFus[] = $fusProcesado['fus_cuerpo'][$FusSysadminController->appsTitulos($rows['applications_id'])];

            $urlAnexos = new RelAnexosFus;
            $anexos = $urlAnexos->getAllsSRCAnexo($rows['applications_id'], $rows['fus_sysadmin_wtl_id']);
            $rows['extiste_anexo'] = count($anexos);

            switch (count($anexos)) {
                case 1:
                    $rows['path_anexos'] = $anexos[0]['path'];
                    break;
                case 0:
                    break;
                default:
                    $rows['path_anexos'] = $anexos;
                    break;
            }

            $rows['detalles'] = json_encode($detallasFus);
            $rows['estadoActual'] = $estadoActual;
            array_push($appsAtualizada, $rows);
        }
        
        return $appsAtualizada;
    }

    public function getMailsByIdFUSMesaAutRat($id, $tipoAutorizador, $idapp = null, $idRelConfig = null) {
        if($idRelConfig != null && !empty($idRelConfig)) {
            $info = $this->getInfoCompletaById($id, $idRelConfig);
            return RelConfigurationfussyswtl::select(['fus_configuracion_autorizaciones.applications_id', 'fus_configuracion_autorizaciones.tipo_autorizador', 'fus_configuracion_autorizaciones.correo', 'rel_configuration_fussyswtl.id AS idRelConf'])
            ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $id)
            ->where('fus_configuracion_autorizaciones.estado', '=', '1')
            ->where('rel_configuration_fussyswtl.estado_autorizacion', '=', '0')
            ->where('fus_configuracion_autorizaciones.tipo_autorizador', '=', $tipoAutorizador)
            ->where('fus_configuracion_autorizaciones.applications_id', '=', $idapp)
            ->where('fus_configuracion_autorizaciones.rol_mod_rep', '=', $info['rol_mod_rep'])
            ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
            ->groupby('correo')
            ->get()
            ->toArray();
        } else {
            return RelConfigurationfussyswtl::select(['fus_configuracion_autorizaciones.applications_id', 'fus_configuracion_autorizaciones.tipo_autorizador', 'fus_configuracion_autorizaciones.correo', 'rel_configuration_fussyswtl.id AS idRelConf'])
            ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $id)
            ->where('fus_configuracion_autorizaciones.estado', '=', '1')
            ->where('rel_configuration_fussyswtl.estado_autorizacion', '=', '0')
            ->where('fus_configuracion_autorizaciones.tipo_autorizador', '=', $tipoAutorizador)
            ->where('fus_configuracion_autorizaciones.applications_id', '=', $idapp)
            ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
            ->groupby('correo')
            ->get()
            ->toArray();
        }
    }

    public function verificarTiposAutorizadores($idfus) {
        return RelConfigurationfussyswtl::from('rel_configuration_fussyswtl AS rcf')
            ->select([
            'fca.applications_id',
            DB::raw('
            (
                SELECT 
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
                                tipo_autorizador = 1 
                            AND 
                                rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                            AND
                                fus_configuracion_autorizaciones.applications_id = fca.applications_id
                            AND
                                fus_configuracion_autorizaciones.estado = 1
                            AND
                                rel_configuration_fussyswtl.estado_autorizacion = 1
                            OR
                                tipo_autorizador = 1 
                            AND 
                                rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                            AND
                                fus_configuracion_autorizaciones.applications_id = fca.applications_id
                            AND
                                rel_configuration_fussyswtl.estado_autorizacion = 2
                        ) > 0,
                        0,
                        COUNT(*) 
                    )
                FROM 
                    fus_configuracion_autorizaciones 
                INNER JOIN 
                    rel_configuration_fussyswtl 
                ON 
                    rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                WHERE 
                    tipo_autorizador = 1
                AND 
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                AND
                    fus_configuracion_autorizaciones.estado = 1
            ) AS mesa
            '),
            DB::raw('
            (
                SELECT 
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
                                tipo_autorizador = 2 
                            AND 
                                rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                            AND
                                fus_configuracion_autorizaciones.applications_id = fca.applications_id
                            AND
                                rel_configuration_fussyswtl.estado_autorizacion = 1
                            OR
                                tipo_autorizador = 2 
                            AND 
                                rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                            AND
                                fus_configuracion_autorizaciones.applications_id = fca.applications_id
                            AND
                                fus_configuracion_autorizaciones.estado = 1
                            AND
                                rel_configuration_fussyswtl.estado_autorizacion = 2
                        ) > 0,
                        0,
                        COUNT(*) 
                    )
                FROM 
                    fus_configuracion_autorizaciones 
                INNER JOIN 
                    rel_configuration_fussyswtl 
                ON 
                    rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                WHERE 
                    tipo_autorizador = 2
                AND 
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                AND
                    fus_configuracion_autorizaciones.estado = 1
            ) AS autorizador
            '),
            DB::raw('
            (
                SELECT 
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
                                tipo_autorizador = 3 
                            AND 
                                rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                            AND
                                fus_configuracion_autorizaciones.applications_id = fca.applications_id
                            AND
                                rel_configuration_fussyswtl.estado_autorizacion = 1
                            OR
                                tipo_autorizador = 3 
                            AND 
                                rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                            AND
                                fus_configuracion_autorizaciones.applications_id = fca.applications_id
                            AND
                                fus_configuracion_autorizaciones.estado = 1
                            AND
                                rel_configuration_fussyswtl.estado_autorizacion = 2
                        ) > 0,
                        0,
                        COUNT(*) 
                    )
                FROM 
                    fus_configuracion_autorizaciones 
                INNER JOIN 
                    rel_configuration_fussyswtl 
                ON 
                    rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                WHERE 
                    tipo_autorizador = 3 
                AND 
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                AND
                    fus_configuracion_autorizaciones.estado = 1
            ) AS ratificador
            ')
        ])
        ->join('fus_configuracion_autorizaciones AS fca', 'fca.id', '=', 'rcf.fus_configuracion_autorizaciones_id')
        ->where('rcf.fus_sysadmin_wtl_id', '=', $idfus)
        ->whereRaw('
            fca.applications_id IN (
                SELECT DISTINCT
                    applications_id
                FROM 
                    fus_configuracion_autorizaciones 
                INNER JOIN 
                    rel_configuration_fussyswtl 
                ON 
                    rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                WHERE 
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
            )
        ')
        ->groupby('fca.applications_id')
        ->get()
        ->toArray();
    }

    public function verificarTiposAutorizadoresByIdApp($idfus, $idapp, $idRelConfig = null) {
        if($idRelConfig != null && !empty($idRelConfig)) {
            $info = $this->getInfoCompletaById($idfus, $idRelConfig);
            $complementoQuery = 'AND fus_configuracion_autorizaciones.rol_mod_rep = "'.$info['rol_mod_rep'].'"';
        }
        
        $query = RelConfigurationfussyswtl::from('rel_configuration_fussyswtl AS rcf')
            ->select([
                'fca.applications_id',
                DB::raw('
                (
                    SELECT 
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
                                    tipo_autorizador = 1 
                                AND 
                                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                                AND
                                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                                AND
                                    rel_configuration_fussyswtl.estado_autorizacion = 1
                                '.$complementoQuery.'
                                OR
                                    tipo_autorizador = 1 
                                AND 
                                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                                AND
                                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                                AND
                                    rel_configuration_fussyswtl.estado_autorizacion = 2
                                '.$complementoQuery.'
                            ) > 0,
                            COUNT(*),
                            0
                        )
                    FROM 
                        fus_configuracion_autorizaciones 
                    INNER JOIN 
                        rel_configuration_fussyswtl 
                    ON 
                        rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                    WHERE 
                        tipo_autorizador = 1
                    AND 
                        rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                    AND
                        fus_configuracion_autorizaciones.applications_id = fca.applications_id
                    '.$complementoQuery.'
                ) AS mesa
                '),
                DB::raw('
                (
                    SELECT 
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
                                    tipo_autorizador = 2 
                                AND 
                                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                                AND
                                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                                AND
                                    rel_configuration_fussyswtl.estado_autorizacion = 1
                                '.$complementoQuery.'
                                OR
                                    tipo_autorizador = 2 
                                AND 
                                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                                AND
                                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                                AND
                                    rel_configuration_fussyswtl.estado_autorizacion = 2
                                '.$complementoQuery.'
                            ) > 0,
                            COUNT(*),
                            0
                        )
                    FROM 
                        fus_configuracion_autorizaciones 
                    INNER JOIN 
                        rel_configuration_fussyswtl 
                    ON 
                        rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                    WHERE 
                        tipo_autorizador = 2
                    AND 
                        rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                    AND
                        fus_configuracion_autorizaciones.applications_id = fca.applications_id
                    '.$complementoQuery.'
                ) AS autorizador
                '),
                DB::raw('
                (
                    SELECT 
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
                                    tipo_autorizador = 3 
                                AND 
                                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                                AND
                                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                                AND
                                    rel_configuration_fussyswtl.estado_autorizacion = 1
                                '.$complementoQuery.'
                                OR
                                    tipo_autorizador = 3 
                                AND 
                                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                                AND
                                    fus_configuracion_autorizaciones.applications_id = fca.applications_id
                                AND
                                    rel_configuration_fussyswtl.estado_autorizacion = 2
                                '.$complementoQuery.'
                            ) > 0,
                            COUNT(*),
                            0
                        )
                    FROM 
                        fus_configuracion_autorizaciones 
                    INNER JOIN 
                        rel_configuration_fussyswtl 
                    ON 
                        rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id = fus_configuracion_autorizaciones.id 
                    WHERE 
                        tipo_autorizador = 3 
                    AND 
                        rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                    AND
                        fus_configuracion_autorizaciones.applications_id = fca.applications_id
                    '.$complementoQuery.'
                ) AS ratificador
                ')
            ])
            ->join('fus_configuracion_autorizaciones AS fca', 'fca.id', '=', 'rcf.fus_configuracion_autorizaciones_id')
            ->where('rcf.fus_sysadmin_wtl_id', '=', $idfus)
            ->where('fca.applications_id', '=', $idapp)
            ->groupby('fca.applications_id')
            ->get()
            ->toArray();
            
            return $query;
    }

    public function getRelConfigAutoById($id) {
        return RelConfigurationfussyswtl::find($id);
    }

    public function getRelConfigAutoByIdFus($id) {
        $getTipoAutorizacion = RelConfigurationfussyswtl::select(['fus_configuracion_autorizaciones.tipo_autorizacion', 'rel_configuration_fussyswtl.id'])
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $id)
        ->get()
        ->toArray();
        
        $conteoTiposDeAut = count($getTipoAutorizacion);
        // $verificarAutorizaciones = array();
        $verificarAutorizaciones = RelConfigurationfussyswtl::select(['fus_configuracion_autorizaciones.tipo_autorizacion', 'rel_configuration_fussyswtl.id'])
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $id)
        ->where('rel_configuration_fussyswtl.estado_autorizacion', '=', '1')
        ->get()
        ->toArray();

        if(count($verificarAutorizaciones) == $conteoTiposDeAut) {
            return 1;
        }
        
        return false;
    }

    public function getAutorizacionesDeApps($idfus) {
        return RelConfigurationfussyswtl::where('fus_sysadmin_wtl_id', '=', $idfus)
        ->where('estado_autorizacion', '=', '1')
        ->count();
    }

    public function getValidacionAutorizacion($id) {
        $autorizadorActual = RelConfigurationfussyswtl::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('rel_configuration_fussyswtl.id', '=', $id)
        ->first();

        $existenciaDeAutorizacion = RelConfigurationfussyswtl::join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $autorizadorActual->fus_sysadmin_wtl_id)
        ->where('fus_configuracion_autorizaciones.tipo_autorizador', '=', $autorizadorActual->tipo_autorizador)
        ->where('fus_configuracion_autorizaciones.rol_mod_rep', '=', $autorizadorActual->rol_mod_rep)
        ->where('rel_configuration_fussyswtl.estado_autorizacion', '=', 1)
        ->get()
        ->toArray(); // Pendiente

        return count($existenciaDeAutorizacion);
    }

    public function autorizacionesarevisar($data) {
        return RelConfigurationfussyswtl::selectRaw('
            *,
            rel_configuration_fussyswtl.id AS idrelAut
        ')
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->join('fus_sysadmin_wtl', 'fus_sysadmin_wtl.id', '=', 'rel_configuration_fussyswtl.fus_sysadmin_wtl_id')
        ->where('fus_sysadmin_wtl.id', '=', $data['idFus'])
        ->where('fus_configuracion_autorizaciones.no_empleado_labora', '=', $data['noEmp'])
        ->where('fus_configuracion_autorizaciones.estado', '=', 1)
        ->get()
        ->toArray();
    }

    public function appsOfFus($idFus) {
        return RelConfigurationfussyswtl::select([
            'applications.id',
            'applications.name'
        ])
        ->distinct()
        ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
        ->join('applications', 'applications.id', '=', 'fus_configuracion_autorizaciones.applications_id')
        ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $idFus)
        ->where('fus_configuracion_autorizaciones.estado', '=', 1)
        ->get()
        ->toArray();
    }

    public function cambiarestadoAutorizacion($id, $value, $idFus) {
        $sendMailRechazo = new NotificacionesController;
        
        $autorizacion = RelConfigurationfussyswtl::find($id);
        $autorizacion->estado_autorizacion = $value['accion'];
        $autorizacion->fecha_atencion =  DB::raw('now()');
        $idConfig = $autorizacion->fus_configuracion_autorizaciones_id;
        $autorizacion->save();

        if($value['accion'] == 2) {
            $getInfoActual = new FusConfiguracionesAutorizaciones;
            $datosDeConfigActual = $getInfoActual->getAutorizacionesByID($idConfig);

            $idsConfigACambiar = RelConfigurationfussyswtl::select('rel_configuration_fussyswtl.id')
            ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id')
            ->where('rel_configuration_fussyswtl.fus_sysadmin_wtl_id', '=', $idFus)
            ->where('fus_configuracion_autorizaciones.rol_mod_rep', '=', $datosDeConfigActual->rol_mod_rep)
            ->where('fus_configuracion_autorizaciones.tipo_autorizador', '<>', $datosDeConfigActual->tipo_autorizador)
            ->where('rel_configuration_fussyswtl.estado_autorizacion', '=', 0)
            ->get()
            ->toArray();
            
            foreach($idsConfigACambiar AS $index => $val) {
                $rechazarElResto = RelConfigurationfussyswtl::find($val["id"]);
                $rechazarElResto->estado_autorizacion = 2;
                $rechazarElResto->fecha_atencion = DB::raw('now()');
                $rechazarElResto->save();
            }

            $rechazo = new ObservacionPorRechazo;
            $idObse = $rechazo->insertMotivoRechazo($idFus, 2, $value['motivorechazo'], $id, null);
            $sendMailRechazo->sendMailRechazoSolicitante($idFus, $idObse, 3);
        }
    }

    public function calculoTotal($idFus){
        return RelConfigurationfussyswtl::from('rel_configuration_fussyswtl AS rcf')
        ->selectRaw('
            fca.applications_id,
            fca.rol_mod_rep,
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
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 1
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS conteo_mesas,
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
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 2
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS conteo_autorizadores,
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
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 3
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS conteo_ratificadores,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    rel_configuration_fussyswtl.estado_autorizacion = 1
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 1
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS autorizados_mesas,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    rel_configuration_fussyswtl.estado_autorizacion = 1
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 2
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS autorizados_autorizadores,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    rel_configuration_fussyswtl.estado_autorizacion = 1
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 3
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS autorizados_ratificadores,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    rel_configuration_fussyswtl.estado_autorizacion = 2
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 1
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS rechazados_mesas,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    rel_configuration_fussyswtl.estado_autorizacion = 2
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 2
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS rechazados_autorizadores,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    rel_configuration_fussyswtl.estado_autorizacion = 2
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 3
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS rechazados_ratificadores,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    (rel_configuration_fussyswtl.estado_autorizacion = 1 OR rel_configuration_fussyswtl.estado_autorizacion = 2)
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 1
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS atendidos_mesas,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    (rel_configuration_fussyswtl.estado_autorizacion = 1 OR rel_configuration_fussyswtl.estado_autorizacion = 2)
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 2
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS atendidos_autorizadores,
            (
                SELECT
                    COUNT(*)
                FROM
                    rel_configuration_fussyswtl
                INNER JOIN
                    fus_configuracion_autorizaciones
                ON
                    fus_configuracion_autorizaciones.id = rel_configuration_fussyswtl.fus_configuracion_autorizaciones_id
                WHERE
                    rel_configuration_fussyswtl.fus_sysadmin_wtl_id = rcf.fus_sysadmin_wtl_id
                AND
                    (rel_configuration_fussyswtl.estado_autorizacion = 1 OR rel_configuration_fussyswtl.estado_autorizacion = 2)
                AND
                    fus_configuracion_autorizaciones.rol_mod_rep = fca.rol_mod_rep
                AND
                    fus_configuracion_autorizaciones.tipo_autorizador = 3
                AND 
                    fus_configuracion_autorizaciones.estado = 1
            ) AS atendidos_ratificadores
        ')
        ->join('fus_configuracion_autorizaciones AS fca', 'fca.id', '=', 'rcf.fus_configuracion_autorizaciones_id')
        ->where('rcf.fus_sysadmin_wtl_id', '=', $idFus)
        ->where('fca.estado', '=', 1)
        ->groupby('fca.rol_mod_rep')
        ->groupby('fca.applications_id')
        ->get()
        ->toArray();
    }
}
