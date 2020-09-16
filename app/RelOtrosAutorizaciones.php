<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RelOtrosAutorizaciones extends Model
{
    protected $table = "rel_otro_autorizaciones";
    
    protected $fillable = [
        'id',
        'estado', 
        'fus_sysadmin_wtl_id', 
        'conf_aut_otros_id', 
        'created_at', 
        'updated_at'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function getRelConfigAutoById($id) {
        return RelOtrosAutorizaciones::find($id);
    }

    public function getNumbersEmployeesByIdFus($idFus) {
        $query = RelOtrosAutorizaciones::select(
            DB::raw('GROUP_CONCAT(DISTINCT conf_aut_otros.numero_empleado) AS no_empleados')
        )
        ->join('conf_aut_otros', 'conf_aut_otros.id', '=', 'rel_otro_autorizaciones.conf_aut_otros_id')
        ->join('fus_sysadmin_wtl', 'fus_sysadmin_wtl.id', '=', 'rel_otro_autorizaciones.fus_sysadmin_wtl_id')
        ->where('rel_otro_autorizaciones.fus_sysadmin_wtl_id', '=', $idFus)
        ->first()
        ->toArray();

        return $query['no_empleados'];
    }

    public function getRelConfigAutoByIdFus($id) {
        $getTipoAutorizacion = RelOtrosAutorizaciones::select(['rel_otro_autorizaciones.id'])
        ->join('conf_aut_otros', 'conf_aut_otros.id', '=', 'rel_otro_autorizaciones.conf_aut_otros_id')
        ->where('rel_otro_autorizaciones.fus_sysadmin_wtl_id', '=', $id)
        ->get()
        ->toArray();
        
        $conteoTiposDeAut = count($getTipoAutorizacion);
        $verificarAutorizaciones = array();
        
            $calculoAutorizaciones = RelOtrosAutorizaciones::join('conf_aut_otros', 'conf_aut_otros.id', '=', 'rel_otro_autorizaciones.conf_aut_otros_id')
            ->where('rel_otro_autorizaciones.fus_sysadmin_wtl_id', '=', $id)
            ->where('rel_otro_autorizaciones.estado', '=', '1')
            ->get()
            ->toArray();
            $verificarAutorizaciones= count($calculoAutorizaciones);
        if($verificarAutorizaciones == $conteoTiposDeAut) {
            return 1;
        }
        
        return false;
    }
}
