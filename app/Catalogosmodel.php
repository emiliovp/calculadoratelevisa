<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ControlConfigFuseApp;

use App\Op_cat_model;

class Catalogosmodel extends Model
{
    protected $table = 'catalogos';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id', 
        'cat_nombre', 
        'cat_status', 
        'created_at', 
        'updated_at', 
        'applications_id'
    ];

    public $apps = "";

    public function getcatalogoByIdApp($id) {
        return Catalogosmodel::where('applications_id', '=', $id)
        ->get()
        ->toArray();
    }

    public function listCatalogos() {
        $controlConfigFuseApp = new ControlConfigFuseApp;
        $appsAdmonMesa = $controlConfigFuseApp->usuarioByNoEmpName(Auth::user()->noEmployee, Auth::user()->name);

        if($appsAdmonMesa["todo"] == 1) {
            $this->apps = null;
        } else {
            $this->apps = json_decode($appsAdmonMesa["apps"], true);
        }

        return Catalogosmodel::from('catalogos AS cat')
        ->select([
            'cat.id',
            'cat.cat_nombre',
            DB::raw('
                CASE
                    WHEN cat.cat_status = 1 THEN "Activo"
                    WHEN cat.cat_status = 0 THEN "Inactivo"
                END AS estado
            '),
            'cat.created_at',
            'applications_id',
            DB::raw('(SELECT alias FROM applications WHERE id = cat.applications_id) AS aplicacion')
        ])
        ->where('cat.cat_status', '=', 1)
        ->where(function($query) {
            if($this->apps != null || is_array($this->apps) === true){
                $query->whereIn('cat.applications_id', $this->apps);
            } else {
                $query->whereNotNull('cat.applications_id');
            }
        })
        ->get()
        ->toArray();
    }

    public function altaCatalogo($data) {
        $catalogo = new Catalogosmodel;
        $catalogo->cat_nombre = mb_strtoupper($data["name"]);
        $catalogo->cat_status = 1;

        if($catalogo->save()) {
            return true;
        }

        return false;
    }

    public function eliminacionLogica($id) {
        $catalogos = Catalogosmodel::find($id);
        $catalogos->cat_status = 0;

        if($catalogos->save()) {
            
            $optcat = Op_cat_model::where('catalogos_id', '=', $id);
            $optcat->update(['cat_op_estatus' => 0]);

            return true;
        }
        
        return false;
    }

    public function recuperar_opciones($id)
    {
        return Op_cat_model::select(
            DB::raw('distinct cat_opciones.cat_op_descripcion AS dominio')
        )
        ->join('catalogos', 'catalogos.id', '=', 'cat_opciones.catalogos_id')
        ->where('catalogos.cat_status', '=',1)
        ->where('cat_opciones.cat_op_estatus', '=',1)
        ->whereRaw("catalogos.cat_nombre LIKE '%".$id."%'")
        ->get()
        ->toArray();
    }

    public function getCatalogoYOpciones($catalogo, $subcatalogo, $idapp, $idCatPrin = null, $idAut = null, $catalogo2 = null) {
        if($subcatalogo != null) {
            if($catalogo2 != null) {
                $idPrincipalJerarquia = Catalogosmodel::where('catalogos.cat_nombre', '=', $catalogo2)
                ->join('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
                ->where('cat_opciones.cat_op_descripcion', '=', $subcatalogo)
                ->where('catalogos.applications_id', '=', $idapp)
                ->orderby('cat_opciones.cat_op_descripcion', 'asc')
                ->first();

                if($idPrincipalJerarquia != null) {
                    return Catalogosmodel::where('cat_nombre', '=', $catalogo)
                    ->join('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
                    ->where('cat_opciones_id', '=', $idPrincipalJerarquia->id)
                    ->where('cat_opciones.cat_op_estatus', '=', 1)
                    ->orderby('cat_opciones.cat_op_descripcion', 'asc')
                    ->get()
                    ->toArray();
                }
            } else {
                $idPrincipalJerarquia = Catalogosmodel::where('cat_nombre', '=', $catalogo)
                ->join('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
                ->where('cat_op_descripcion', '=', $subcatalogo)
                ->orderby('cat_opciones.cat_op_descripcion', 'asc')
                ->first();
    
                if($idPrincipalJerarquia != null) {
                    return Catalogosmodel::where('cat_nombre', '=', $catalogo)
                    ->join('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
                    ->where('cat_opciones_id', '=', $idPrincipalJerarquia->id)
                    ->where('cat_opciones.cat_op_estatus', '=', 1)
                    ->orderby('cat_opciones.cat_op_descripcion', 'asc')
                    ->get()
                    ->toArray();
                }
            }
            
        } elseif($idCatPrin != null) {
            return Catalogosmodel::where('cat_nombre', '=', $catalogo)
            ->join('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
            ->where('cat_opciones_id', '=', $idCatPrin)
            ->where('cat_opciones.cat_op_estatus', '=', 1)
            ->orderby('cat_opciones.cat_op_descripcion', 'asc')
            ->get()
            ->toArray();
        } else if($idAut != null) {
            return Catalogosmodel::select('cat_opciones.id', 'cat_opciones.cat_op_descripcion')
            ->join('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
            ->join('rel_catopciones_configuracionesautorizaciones', 'rel_catopciones_configuracionesautorizaciones.cat_opciones_id', '=', 'cat_opciones.id')
            ->join('fus_configuracion_autorizaciones', 'fus_configuracion_autorizaciones.id', '=', 'rel_catopciones_configuracionesautorizaciones.fus_configuracion_autorizaciones_id')
            ->whereRaw('fus_configuracion_autorizaciones.rol_mod_rep = (select rol_mod_rep from fus_configuracion_autorizaciones where id ='.$idAut.')')
            ->where('cat_opciones.cat_op_estatus', '=', 1)
            ->groupBy('cat_opciones.cat_op_descripcion')
            ->orderby('cat_opciones.cat_op_descripcion', 'asc')
            ->get()
            ->toArray();
        } else {
            return Catalogosmodel::where('cat_nombre', '=', $catalogo)
            ->join('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
            ->where('catalogos.applications_id', '=', $idapp)
            ->where('cat_opciones.cat_op_estatus', '=', 1)
            ->orderby('cat_opciones.cat_op_descripcion', 'asc')
            ->get()
            ->toArray();
        }
    }

    public function getMesasControl() {
        return Op_cat_model::select(['cat_opciones.id','cat_opciones.cat_op_descripcion'])
        ->join("catalogos", "catalogos.id", "=", "cat_opciones.catalogos_id")
        ->where("catalogos.cat_nombre", "=", "MESAS DE CONTROL")
        ->get()
        ->toArray();
    }
    public function catalogosByName($catalogo){
        return Catalogosmodel::where('cat_nombre', '=', $catalogo)
            ->get()
            ->toArray();
    }
    public function catalogosByApp($app){
        return Catalogosmodel::where('applications_id', '=', $app)
            ->where('cat_status','=','1')
            ->get()
            ->toArray();
    }
    public function catalogoById($id) {
        return Catalogosmodel::where('id', '=', $id)
        ->get()
        ->toArray();
    }
    public function catalogosByOpt($idopt){
        return Catalogosmodel::select(['catalogos.id', 
            'catalogos.cat_nombre', 
            'catalogos.cat_status', 
            'catalogos.applications_id'])
            ->leftjoin('cat_opciones', 'cat_opciones.catalogos_id', '=', 'catalogos.id')
            ->where('cat_opciones.id','=', $idopt)
            ->get()
            ->toArray();
    }
}
