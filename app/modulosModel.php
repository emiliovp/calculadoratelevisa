<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class modulosModel extends Model
{
    protected $table = 'fus_modulos_acceso';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id', 
        'modulo', 
        'estado',  
        'created_at',
        'updated_at'
    ];
    public function modulosActivos(){
        return modulosModel::where('estado','=', 'Activo')
        ->orderBy('modulo')
        ->get()
        ->toArray();
    }
    public function modulosconcat($id){
        $a = modulosModel::select([
            "modulo"
        ])
        ->where('estado','=', 'Activo')
        ->whereIn('id',$id)
        ->orderBy('modulo')
        ->get()
        ->toArray();
        return $a;
    }
    public function modulosByName($mod){

        $a = modulosModel::select([
            DB::raw('group_concat(alias) as alias'),
           DB::raw('group_concat(id SEPARATOR "_") as idmod') 
        ])
        ->where('estado','=', 'Activo')
        ->whereIn('modulo',$mod)
        ->orderBy('modulo')
        ->get()
        ->toArray();
        return $a;
    }
}
