<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\RelCatOpcionesConfiguracionesAutorizaciones;

class Op_cat_model extends Model
{
    protected $table = 'cat_opciones';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id', 
        'cat_op_descripcion', 
        'cat_op_estatus', 
        'created_at', 
        'updated_at', 
        'catalogos_id', 
        'jerarquia', 
        'cat_opciones_id'
    ];

    public function getOptCatalogoByIdApp($id) {
        return Op_cat_model::where('catalogos_id', '=', $id)
        ->get()
        ->toArray();
    }

    public function getOptCatalogoById($id) {
        return Op_cat_model::where('id', '=', $id)
        ->first()
        ->toArray();
    }
    public function getOptCatalogoByIdForFormEdit($id) {
        $query = Op_cat_model::select([
            'id', 
            'cat_op_descripcion', 
            'cat_op_estatus', 
            'created_at', 
            'updated_at', 
            'catalogos_id', 
            'jerarquia', 
            'cat_opciones_id'
            // DB::raw('(SELECT GROUP_CONCAT(fus_configuracion_autorizaciones_id SEPARATOR "_") FROM rel_catopciones_configuracionesautorizaciones WHERE cat_opciones_id ='.$id.') AS id_rel_aut')
        ])
        ->where('id', '=', $id)
        ->first()
        ->toArray();
        
        $query2 = RelCatOpcionesConfiguracionesAutorizaciones::select("fus_configuracion_autorizaciones_id")
        ->where('cat_opciones_id', '=', $id)
        ->get()
        ->toArray();

        $id_rel_aut = "";
        foreach($query2 AS $row) {
            $id_rel_aut .= $row["fus_configuracion_autorizaciones_id"]."_";
        }
        $id_rel_aut = substr($id_rel_aut, 0, -1);

        $query["id_rel_aut"] = $id_rel_aut;
        
        return $query;
    }

    public function guardarOpt($nomb, $idCat, $idOptCat = null, $jerarquia) {
        $opt = new Op_cat_model;
        $opt->cat_op_descripcion = $nomb;
        $opt->catalogos_id = $idCat;
        $opt->jerarquia = $jerarquia;
        $opt->cat_opciones_id = $idOptCat;

        $opt->save();
        return $opt->id;
    }

    public function guardarEdicionOpt($idAEditar, $nomb, $idCat, $idOptCat = null, $jerarquia) {
        $opt = Op_cat_model::find($idAEditar);
        $opt->cat_op_descripcion = $nomb;
        $opt->catalogos_id = $idCat;
        $opt->jerarquia = $jerarquia;
        $opt->cat_opciones_id = $idOptCat;

        $opt->save();
    }

    public function getOpciones($option){
        $id = Op_cat_model::select('cat_opciones_id')
        ->where('cat_op_estatus','=', 1)
        ->whereRaw("cat_op_descripcion LIKE '".$option."%'")
        ->get()
        ->toArray();
        $a=array();
        foreach ($id as $key => $value) {
            $a[$key]=$value['cat_opciones_id'];
        }
        return Op_cat_model::select(['cat_op_descripcion'])
        ->where('catalogos_id', '=', 1)
        ->where('cat_op_estatus', '=', 1)
        // ->whereRaw("id in (select cat_opciones_id from cat_opciones where cat_op_descripcion like '%".$option."%'")
        ->whereIn('id', $a)
        ->get()
        ->toArray();
    } 

    public function getListaOpciones($id) {
        return Op_cat_model::from('cat_opciones')
        ->select([
            'id',
            DB::raw('
                CASE
                    WHEN cat_op_estatus = 1 THEN "Activo"
                    WHEN cat_op_estatus = 0 THEN "Inactivo"
                END AS estado
            '),
            DB::raw('
                (SELECT COUNT(id) FROM rel_catopciones_configuracionesautorizaciones WHERE cat_opciones_id = cat_opciones.id) AS dependencias
            '),
            'jerarquia',
            'cat_opciones_id',
            'cat_op_descripcion',
            'created_at'
        ])
        ->where('catalogos_id', '=', $id)
        ->where('cat_op_estatus', '=', 1)
        ->get()
        ->toArray();
    }

    public function eliminacionLogica($id) {
        $optcat = Op_cat_model::find($id);
        $optcat->cat_op_estatus = 0;

        if($optcat->save()) {
            return true;
        }
        
        return false;
    }
    public function getOptMesas($id){
        return Op_cat_model::select(['id','cat_op_descripcion'])
        ->where('catalogos_id', '=', $id)
        ->where('cat_op_estatus', '=', 1)
        ->get()
        ->toArray();
    }
    public function eliminacionLogicaMesa($id) {
        $mesas = Op_cat_model::find($id);
        $mesas->cat_op_estatus = 0;

        if($mesas->save()) {
            return true;
        }
        
        return false;
    }
    public function alta_mesa($nombre){
        $mesa = new Op_cat_model;
        $mesa->cat_op_descripcion = mb_strtoupper($nombre);
        $mesa->cat_op_estatus = 1;
        $mesa->catalogos_id = 58;
        $mesa->jerarquia = 1;
        if($mesa->save()) {
            return true;
        }

        return false;
    }
    public function editMesa($idAEditar, $nombre){
        $mesa = Op_cat_model::find($idAEditar);
        $mesa->cat_op_descripcion = $nombre;
        
        if($mesa->save()) {
            return true;
        }

        return false;
    }
    public function getOptByCatalogo($id) {
        return Op_cat_model::where('catalogos_id', '=', $id)
        ->where('cat_op_estatus','=','1')
        ->get()
        ->toArray();
    }
    public function optCatOpPadre($idcat){
        return DB::table('cat_opciones as a')
        ->leftjoin('cat_opciones as b','b.id','=','a.cat_opciones_id')
        ->select('a.id',DB::raw('
        if(a.cat_opciones_id is null, a.cat_op_descripcion,concat(b.cat_op_descripcion," - ", a.cat_op_descripcion)) as cat_op_descripcion
        '),
        'a.cat_op_estatus', 
        'a.catalogos_id', 
        'a.jerarquia', 
        'a.cat_opciones_id'
        )
        ->where('a.catalogos_id', '=', $idcat)
        ->get()
        ->toArray();
    }
}
