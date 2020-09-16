<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\ControlConfigFuseApp;

class ListaResponsabilidades extends Model
{
    protected $table = "listaresponsabilidades";

    protected $fillable = [
        'idFusResp', 
        'alias_instancia', 
        'alias', 
        'correo', 
        'rol_mod_rep', 
        'no_empleado_labora', 
        'nombre_labora', 
        'usuario_red', 
        'fecha_creacion', 
        'path', 
        'autorizacion',
        'applications_id'
    ];

    protected $hidden = [];
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function responsabilidades() {
        $controlConfigFuseApp = new ControlConfigFuseApp;
        $appsAdmonMesa = $controlConfigFuseApp->usuarioByNoEmpName(Auth::user()->noEmployee, Auth::user()->name);

        if($appsAdmonMesa["todo"] == 1) {
            $this->apps = null;
        } else {
            $this->apps = json_decode($appsAdmonMesa["apps"], true);
        }

        return ListaResponsabilidades::where(function($query) {
            if($this->apps != null || is_array($this->apps) === true){
                $query->whereIn('applications_id', $this->apps);
            } else {
                $query->whereNotNull('applications_id');
            }
        })
        ->get()
        ->toArray();
    }
}
